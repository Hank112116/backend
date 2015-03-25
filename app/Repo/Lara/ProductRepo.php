<?php namespace Backend\Repo\Lara;

use Backend\Model\ProjectModifier;
use Carbon;
use ImageUp;
use Backend\Model\Eloquent\Project;
use Illuminate\Database\Eloquent\Collection;
use Backend\Repo\RepoInterfaces\ProductInterface;
use Backend\Repo\RepoInterfaces\DuplicateProductInterface;
use Backend\Repo\RepoInterfaces\PerkInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoTrait\PaginateTrait;
use Backend\Repo\RepoTrait\TimeProcessTrait;

class ProductRepo implements ProductInterface
{
    use PaginateTrait;
    use TimeProcessTrait;

    private $wait_approve;
    protected $with_relations = ['user', 'category'];

    public function __construct(
        Project $project,
        DuplicateProductInterface $duplicate,
        PerkInterface $perk,
        UserInterface $user,
        ProjectModifier $project_modifier,
        ImageUp $image_uploader
    ) {
        $this->project  = $project;
        $this->duplicate_repo = $duplicate;

        $this->perk_repo      = $perk;
        $this->user_repo      = $user;

        $this->project_modifier = $project_modifier;

        $this->image_uploader = $image_uploader;
    }

    public function duplicateRepo()
    {
        return $this->duplicate_repo;
    }

    public function find($id)
    {
        $project = $this->project->queryProduct()->find($id);

        return $project;
    }

    public function findDuplicate($id)
    {
        $duplicate = $this->duplicate_repo->queryProduct()->find($id);

        return $duplicate;
    }

    public function all()
    {
        return $this->project->with($this->with_relations)
            ->queryProduct()
            ->orderBy('project_id', 'desc')
            ->get();
    }

    public function byPage($page = 1, $limit = 20)
    {
        $projects = $this->modelBuilder($this->project, $page, $limit)
            ->with($this->with_relations)
            ->queryProduct()
            ->get();

        $this->setPaginateTotal($this->project->queryProduct()->count());

        return $this->getPaginateContainer($this->project, $page, $limit, $projects);
    }

    public function byUserId($user_id)
    {
        $projects = $this->project->with($this->with_relations)
            ->queryProduct()
            ->where('user_id', $user_id)
            ->orderBy('project_id', 'desc')
            ->get();

        return $projects;
    }

    public function byUserName($name)
    {
        $users = $this->user_repo->byName($name);
        if ($users->count() == 0) {
            return new Collection();
        }

        $projects = $this->project->with($this->with_relations)
            ->queryProduct()
            ->whereIn('user_id', $users->lists('user_id'))
            ->orderBy('project_id', 'desc')
            ->get();

        return $projects;
    }

    public function byProjectId($project_id = '')
    {
        if (!$project_id) {
            return new Collection();
        }

        $projects = $this->project->with($this->with_relations)
            ->queryProduct()
            ->where('project_id', $project_id)
            ->get();

        return $projects;
    }

    public function byTitle($title = '')
    {
        if (!$title) {
            return new Collection();
        }

        $trimmed = preg_replace('/\s+/', ' ', $title); //replace multiple space to one
        $keys    = explode(' ', $trimmed);

        return $this->project->with($this->with_relations)
            ->queryProduct()
            ->where(
                function ($query) use ($keys) {
                    foreach ($keys as $k) {
                        $query->orWhere('project_title', 'LIKE', "%{$k}%");
                    }
                }
            )->orderBy('project_id', 'desc')->get();
    }

    public function byDateRange($dstart = '', $dend = '')
    {
        if (!$dstart and !$dend) {
            return new Collection();
        }

        $dstart = $dstart ? Carbon::parse($dstart)->startOfDay() : Carbon::now()->startOfMonth()->startOfDay();
        $dend   = $dend ? Carbon::parse($dend)->endOfDay() : Carbon::now()->endOfDay();

        return $this->project->whereBetween('update_time', [$dstart, $dend])
            ->queryProduct()
            ->orderBy('project_id', 'desc')
            ->get();
    }

    public function waitApproves()
    {
        $project_ids = $this->waitApproveDraftAndOngoingIds() ?: [0];
        $projects    = $this->project
            ->queryProduct()
            ->whereIn('project_id', $project_ids)
            ->orderBy('project_id', 'desc')
            ->get();

        return $projects;
    }

    public function isWaitApproveOngoing($id)
    {
        return in_array($id, $this->duplicate_repo->waitApproveProjectIds());
    }

    public function hasWaitApproveProject()
    {
        return count($this->waitApproveDraftAndOngoingIds()) > 0;
    }

    private function waitApproveDraftAndOngoingIds()
    {
        return array_merge(
            $this->waitApproveProjectIds(),
            $this->duplicate_repo->waitApproveProjectIds()
        );
    }

    private function waitApproveProjectIds()
    {
        if (!isset($this->wait_approve)) {
            $this->wait_approve = $this->project
                ->queryApprovePending()
                ->lists('project_id');
        }

        return $this->wait_approve;
    }

    /*
     * @param Paginator|Collection
     * return array
     */
    public function toOutputArray($projects)
    {
        $output = [];
        foreach ($projects as $project) {
            $output[ ] = $this->productOutput($project);
        }

        return $output;
    }

    private function productOutput(Project $project)
    {
        return [
            '#'             => $project->project_id,
            'Title'         => $project->project_title,
            'Category'      => $project->textCategory(),
            'Member'        => $project->textUserName(),
            'Country'       => $project->project_country,
            'City'          => $project->project_city,

            'Goal'          => $project->amount,
            'Raised'        => $project->amount_get,

            'Expert Order Days' => $project->manufactuer_total_days,
            'Customer Order Days' => $project->total_days,

            'Expert Order End' => $this->toDateString($project->manufactuer_end_date),
            'Customer Order End' => $this->toDateString($project->end_date),

            'Status'        => $project->profile->text_status,
            'Submit'        => $project->profile->text_product_submit,
            'Hub'           => $project->schedule ? 'Y' : '',

            'Create Date'   => $project->date_added,
            'Last Update'   => $project->update_time,
            'Approve Date'  => $this->toDateTimeString($project->approve_time),
            'Postpone Date' => $this->toDateTimeString($project->postpone_time),

            'Brief'         => e($project->project_summary),
        ];
    }

    public function approve($project_id)
    {
        $this->project_modifier->approveProduct($project_id);
    }

    public function reject($project_id)
    {
        $this->project_modifier->rejectProduct($project_id);
    }

    public function update($project_id, $data)
    {
        $this->project_modifier->updateProduct($project_id, $data);

        if (array_key_exists('perks', $data)) {
            $this->perk_repo->updateProjectPerks($project_id, $data[ 'perks' ]);
        }
    }

    public function postpone($project_id)
    {
        $this->project_modifier->postponeProduct($project_id);
    }

    public function recoverPostpone($project_id)
    {
        $this->project_modifier->recoverPostponeProduct($project_id);
    }
}
