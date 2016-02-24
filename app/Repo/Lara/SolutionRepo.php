<?php namespace Backend\Repo\Lara;

use ImageUp;
use Backend\Model\Eloquent\Solution;
use Backend\Model\Eloquent\Feature;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoInterfaces\SolutionInterface;
use Backend\Repo\RepoInterfaces\DuplicateSolutionInterface;
use Backend\Model\ModelInterfaces\SolutionModifierInterface;
use Backend\Model\ModelInterfaces\ProjectTagBuilderInterface;
use Backend\Model\ModelInterfaces\FeatureModifierInterface;
use Backend\Model\Plain\SolutionCategory;
use Backend\Model\Plain\SolutionCertification;
use Illuminate\Support\Collection;
use Backend\Repo\RepoTrait\PaginateTrait;

class SolutionRepo implements SolutionInterface
{
    use PaginateTrait;

    private $wait_approve;
    private $solution;
    private $duplicate;
    private $project_tag_builder;
    private $solution_modifier;
    private $category;
    private $certification;
    private $image_uploader;
    private $feature;

    public function __construct(
        Solution $solution,
        DuplicateSolutionInterface $duplicate,
        UserInterface $user,
        SolutionModifierInterface $solution_modifier,
        ProjectTagBuilderInterface $project_tag_builder,
        FeatureModifierInterface $feature_modify,
        SolutionCategory $category,
        SolutionCertification $certification,
        ImageUp $uploader
    ) {
        $this->solution            = $solution;
        $this->duplicate           = $duplicate;
        $this->user                = $user;
        $this->project_tag_builder = $project_tag_builder;
        $this->solution_modifier   = $solution_modifier;
        $this->category            = $category;
        $this->certification       = $certification;
        $this->image_uploader      = $uploader;
        $this->feature             = $feature_modify;
    }

    public function duplicateRepo()
    {
        return $this->duplicate;
    }

    public function find($id)
    {
        return $this->solution->find($id);
    }

    public function findSolution($id)
    {
        return $this->solution
                    ->where('solution_id', $id)
                    ->querySolution()
                    ->first();
    }

    public function findProgram($id)
    {
        return $this->solution
                    ->where('solution_id', $id)
                    ->queryProgram()
                    ->first();
    }

    public function findDuplicate($id)
    {
        return $this->duplicate->find($id);
    }

    public function isWaitApproveOngoing($solution_id)
    {
        $duplicate_ids = $this->duplicate->waitApproveSolutionIds()->toArray();

        return in_array($solution_id, $duplicate_ids);
    }

    public function all()
    {
        return $this->solution->all();
    }

    public function approvedSolutions($page = 1, $limit = 20)
    {
        $this->setPaginateTotal($this->solution->queryAfterSubmitted()->count());

        $collection = $this->modelBuilder($this->solution, $page, $limit)->with('user')
            ->queryAfterSubmitted()
            ->queryNotDeleted()
            ->get();

        $collection = $this->configApprove($collection);

        return $this->getPaginateContainer($this->solution, $page, $limit, $collection);
    }

    public function drafts()
    {
        $solutions = $this->solution->with('user')
            ->queryDraft()
            ->queryNotDeleted()
            ->orderBy('solution_id', 'desc')
            ->get();

        return $solutions;
    }

    public function waitApproveSolutions()
    {
        $solution_ids = $this->waitApproveDraftAndOngoingIds() ?: [0];

        $solutions = $this->solution->with('user')
            ->queryNotDeleted()
            ->whereIn('solution_id', $solution_ids)
            ->orderBy('solution_id', 'desc')
            ->get();

        return $this->configApprove($solutions);
    }

    public function deletedSolutions()
    {
        return $this->solution
            ->queryDeleted()
            ->orderBy('solution_id', 'desc')
            ->get();
    }

    public function program()
    {
        return $this->solution
            ->queryProgram()
            ->orderBy('solution_id', 'desc')
            ->get();
    }

    public function pendingProgram()
    {
        return $this->solution
            ->queryPendingProgram()
            ->orderBy('solution_id', 'desc')
            ->get();
    }

    public function pendingSolution()
    {
        return $this->solution
            ->queryPendingSolution()
            ->orderBy('solution_id', 'desc')
            ->get();
    }

    public function byUserName($name)
    {
        $users = $this->user->byName($name);
        if ($users->count() == 0) {
            return new Collection();
        }

        $solutions = $this->solution->with('user')
            ->whereIn('user_id', $users->lists('user_id'))
            ->orderBy('solution_id', 'desc')
            ->get();

        return $this->configApprove($solutions);
    }

    public function byTitle($title = '')
    {
        if (!$title) {
            return new Collection();
        }

        $trimmed = preg_replace('/\s+/', ' ', $title); //replace multiple space to one
        $keys    = explode(' ', $trimmed);

        $solutions = $this->solution->with('user')
            ->orderBy('solution_id', 'desc');

        foreach ($keys as $k) {
            $solutions->orWhere('solution_title', 'LIKE', "%{$k}%");
        }

        return $this->configApprove($solutions->get());
    }

    public function configApprove($solutions)
    {
        $drafts     = $this->waitApproveSolutionIds()->toArray();
        $ongoings   = $this->duplicate->waitApproveSolutionIds()->toArray();
        $duplicates = $this->duplicate->waitApproveSolutions();

        foreach ($solutions as $solution) {
            $solution->is_wait_approve_draft   = in_array($solution->solution_id, $drafts);
            $solution->is_wait_approve_ongoing = in_array($solution->solution_id, $ongoings);

            if ($solution->is_wait_approve_ongoing) {
                $solution->is_manager_approved = $this->isDuplicateSolutionManagerApproved($duplicates, $solution);
            }
        }

        return $solutions;
    }

    private function isDuplicateSolutionManagerApproved(Collection $duplicates, $solution)
    {
        $filters = $duplicates->filter(
            function ($d) use ($solution) {
                return $d->solution_id == $solution->solution_id;
            }
        );

        return $filters->first() and $filters->first()->is_manager_approved;
    }

    public function waitApproveSolutionIds()
    {
        if (!isset($this->wait_approve)) {
            $this->wait_approve = $this->solution
                ->queryWaitApproved()
                ->lists('solution_id');
        }

        return $this->wait_approve;
    }

    public function waitApproveDraftAndOngoingIds()
    {
        return array_merge(
            $this->waitApproveSolutionIds()->toArray(),
            $this->duplicate->waitApproveSolutionIds()->toArray()
        );
    }

    public function hasWaitApproveSolution()
    {
        return count($this->waitApproveDraftAndOngoingIds()) > 0;
    }

    public function hasWaitManagerApproveSolution()
    {
        $solutions = $this->waitApproveSolutions();
        $solutions = $this->filterWaitManagerApproveSolutions($solutions);

        return $solutions->count() > 0;
    }

    public function hasProgram()
    {
        $solutions = $this->solution
                    ->queryProgram()
                    ->get();
        return $solutions->count() > 0;
    }

    public function hasPendingSolution()
    {
        $solutions = $this->solution
                    ->QueryPendingSolution()
                    ->get();
        return $solutions->count() > 0;
    }

    public function hasPendingProgram()
    {
        $solutions = $this->solution
                    ->QueryPendingProgram()
                    ->get();
        return $solutions->count() > 0;
    }

    private function filterWaitManagerApproveSolutions(Collection $solutions)
    {
        return $solutions->filter(
            function ($solution) {
                return !$solution->is_manager_approved;
            }
        );
    }

    public function categoryOptions()
    {
        return $this->category->options();
    }

    public function certificationOptions()
    {
        return $this->certification->options();
    }

    public function onShelf($solution_id)
    {
        $this->solution_modifier->onShelf($solution_id);
    }

    public function offShelf($solution_id)
    {
        $this->solution_modifier->offShelf($solution_id);

        $duplicate = $this->duplicateRepo()->find($solution_id);
        if ($duplicate) {
            $duplicate->delete();
        }
    }

    public function update($solution_id, $data)
    {
        $this->solution_modifier->update($solution_id, $data);
    }

    public function approve($solution_id, $is_manager)
    {
        if ($is_manager) {
            $this->solution_modifier->managerApprove($solution_id, $is_manager);
        } else {
            $this->solution_modifier->approve($solution_id, $is_manager);
        }
    }
    public function toProgram($solution_id, $is_manager)
    {
        if ($is_manager) {
            $this->solution_modifier->managerToProgram($solution_id, $is_manager);
        } else {
            $this->solution_modifier->toProgram($solution_id, $is_manager);
            $featureDate['block_data']    = $solution_id;
            $featureDate['block_type']    = 'solution';
            $featureDate['to_block_type'] = 'program';
            $this->feature->updateType($featureDate);
        }
    }
    public function toSolution($solution_id, $is_manager)
    {
        if ($is_manager) {
            $this->solution_modifier->managerToSolution($solution_id, $is_manager);
        } else {
            $this->solution_modifier->toSolution($solution_id, $is_manager);
            $featureDate['block_data']    = $solution_id;
            $featureDate['block_type']    = 'program';
            $featureDate['to_block_type'] = 'solution';
            $this->feature->updateType($featureDate);
        }
    }

    public function reject($solution_id)
    {
        $this->solution_modifier->reject($solution_id);
    }

    /*
     * @param Paginator|Collection
     * return array
     */
    public function toOutputArray($solutions)
    {
        $output = [];
        foreach ($solutions as $solution) {
            $output[ ] = $this->solutionOutput($solution);
        }

        return $output;
    }

    private function solutionOutput(Solution $solution)
    {
        return [
            '#'            => $solution->solution_id,
            'Title'        => $solution->textTitle(),
            'Category'     => $solution->textMainCategory().', '.$solution->textSubCategory(),
            'Member'       => $solution->textUserName(),
            'Country'      => $solution->user->country,
            'City'         => $solution->user->city,
            'Pairing Tags' => $this->project_tag_builder->projectTagOutput($solution->getProjectTagsAttribute()),
            'Brief'        => $solution->solution_summary,
            'Status'       => $solution->textStatus(),
            'Approve Date' => $solution->approve_time,
            'Host ip'      => $solution->host_ip
        ];
    }
}
