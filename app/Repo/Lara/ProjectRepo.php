<?php namespace Backend\Repo\Lara;

use Carbon;
use Backend\Model\Eloquent\Project;
use Backend\Model\Eloquent\ProjectCategory;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Model\ModelInterfaces\TagBuilderInterface;
use Backend\Model\ModelInterfaces\ProjectTagBuilderInterface;
use Backend\Model\ModelInterfaces\ProjectModifierInterface;
use Backend\Repo\RepoTrait\PaginateTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ProjectRepo implements ProjectInterface
{
    use PaginateTrait;

    protected $with_relations = ['user', 'propose', 'recommendExperts', 'projectTeam', 'internalProjectMemo'];

    private $adminer;
    private $project;
    private $category;
    private $user_repo;
    private $tag_builder;
    private $project_tag_builder;
    private $project_modifier;

    public function __construct(
        AdminerInterface $adminer,
        Project $project,
        ProjectCategory $category,
        UserInterface $user,
        TagBuilderInterface $tag_builder,
        ProjectTagBuilderInterface $project_tag_builder,
        ProjectModifierInterface $project_modifier
    ) {
        $this->adminer             = $adminer;
        $this->project             = $project;
        $this->category            = $category;
        $this->user_repo           = $user;
        $this->tag_builder         = $tag_builder;
        $this->project_tag_builder = $project_tag_builder;
        $this->project_modifier    = $project_modifier;
    }

    public function find($id)
    {
        $project = $this->project->find($id);

        return $project;
    }

    public function all()
    {
        return $this->project
            ->with($this->with_relations)
            ->orderBy('project_id', 'desc')
            ->get();
    }

    public function deletedProjects()
    {
        return $this->project
            ->queryDeleted()
            ->orderBy('project_id', 'desc')
            ->get();
    }

    public function byPage($page = 1, $limit = 20)
    {
        $projects = $this->modelBuilder($this->project, $page, $limit)
            ->with($this->with_relations)
            ->get();

        $this->setPaginateTotal($this->project->count());

        return $this->getPaginateContainer($this->project, $page, $limit, $projects);
    }

    public function byUserId($user_id)
    {
        $projects = $this->project->with($this->with_relations)
            ->where('user_id', $user_id)
            ->orderBy('project_id', 'desc')
            ->get();

        return $projects;
    }

    public function byUnionSearch($input, $page, $per_page)
    {
        /* @var Collection $projects */
        $projects = $this->project->with($this->with_relations)->orderBy('project_id', 'desc')->get();

        if (!empty($input['project_title'])) {
            $project_title = $input['project_title'];
            $projects = $projects->filter(function (Project $item) use ($project_title) {
                if (stristr($item->project_title, $project_title)) {
                    return $item;
                }
            });
        }

        if (!empty($input['project_id'])) {
            $project_id = trim($input['project_id']);
            $projects = $projects->filter(function (Project $item) use ($project_id) {
                if ($item->project_id == $project_id) {
                    return $item;
                }
            });
        }

        if (!empty($input['user_name'])) {
            $user_name = $input['user_name'];
            $projects = $projects->filter(function (Project $item) use ($user_name) {
                if (stristr($item->user->textFullName(), $user_name)) {
                    return $item;
                }
            });
        }

        if (!empty($input['assigned_pm'])) {
            $assigned_pm = explode(',', $input['assigned_pm']);
            $projects = $projects->filter(function (Project $item) use ($assigned_pm) {
                if ($item->internalProjectMemo) {
                    $project_managers = json_decode($item->internalProjectMemo->project_managers, true);
                    if ($project_managers) {
                        $adminers = $this->adminer->findAssignedProjectPM($assigned_pm);
                        if ($adminers) {
                            foreach ($adminers as $adminer) {
                                if (in_array($adminer->hwtrek_member, $project_managers)) {
                                    return $item;
                                }
                            }
                        }
                    }
                }
            });
        }

        if (!empty($input['description'])) {
            $description = $input['description'];
            $projects = $projects->filter(function (Project $item) use ($description) {
                if ($item->internalProjectMemo) {
                    if (stristr($item->internalProjectMemo->description, $description)) {
                        return $item;
                    }
                }
            });
        }

        if (!empty($input['report_action'])) {
            $action = $input['report_action'];
            $projects = $projects->filter(function (Project $item) use ($action) {
                if ($item->internalProjectMemo) {
                    if (stristr($item->internalProjectMemo->report_action, $action)) {
                        return $item;
                    }
                }
            });
        }

        if (!empty($input['country'])) {
            $country = $input['country'];
            $projects = $projects->filter(function (Project $item) use ($country) {
                if (stristr($item->project_country, $country)) {
                    return $item;
                }
            });
        }

        if (!empty($input['status'])) {
            if ($input['status'] != 'all') {
                $status   = $input['status'];
                $projects = $projects->filter(function (Project $item) use ($status) {
                    switch ($status) {
                        case 'public':
                            if ($item->profile->isPublic()) {
                                return $item;
                            }
                            break;
                        case 'private':
                            if ($item->profile->isPrivate()) {
                                return $item;
                            }
                            break;
                        case 'draft':
                            if ($item->profile->isDraft()) {
                                return $item;
                            }
                            break;
                    }
                });
            }
        }

        if (!empty($input['tag'])) {
            $search_tag = $this->tag_builder->tagTransformKey($input['tag']);
            $projects   = $projects->filter(function (Project $item) use ($search_tag) {

                if ($item->isSimilarTag($search_tag)) {
                    return $item;
                }
                $internal_tag = [];
                if ($item->internalProjectMemo) {
                    if ($item->internalProjectMemo->tags) {
                        $internal_tag = explode(',', $item->internalProjectMemo->tags);
                    }
                    if ($internal_tag) {
                        foreach ($internal_tag as $tag) {
                            if (stristr($tag, $search_tag)) {
                                return $item;
                            }
                        }
                    }
                }
            });
        }

        if (!empty($input['dstart']) and !empty($input['time_type'])) {
            $dstart = $input['dstart'];

            if (!empty($input['dend'])) {
                $dend = Carbon::parse($input['dend'])->addDay()->toDateString();
            } else {
                $dend = Carbon::tomorrow()->toDateString();
            }

            $time_type = $input['time_type'];

            $projects = $projects->filter(function (Project $item) use ($dstart, $dend, $time_type) {
                switch ($time_type)
                {
                    case 'update':
                        $update_time = Carbon::parse($item->update_time)->toDateString();
                        if ($update_time < $dend && $update_time >= $dstart) {
                            return $item;
                        }
                        break;
                    case 'create':
                        $create_time = Carbon::parse($item->date_added)->toDateString();
                        if ($create_time < $dend && $create_time >= $dstart) {
                            return $item;
                        }
                        break;
                    case 'release':
                        if ($item->recommendExperts()->count() > 0) {
                            $recommend_experts = $item->recommendExperts()->getResults();
                            $release_time = Carbon::parse($recommend_experts[0]->date_send)->toDateString();
                            if ($release_time < $dend && $release_time >= $dstart) {
                                return $item;
                            }
                        }
                        break;
                    case 'match':
                        $create_time = Carbon::parse($item->date_added)->toDateString();
                        if ($item->hasProposeSolution($dstart, $dend) or
                            $item->hasRecommendExpert($dstart, $dend) or
                            ($create_time < $dend && $create_time >= $dstart)
                        ) {
                            return $item;
                        }
                        break;
                }
            });
        }
        $statistics = $this->getProjectStatistics($projects);
        $projects   = $this->getPaginateFromCollection($projects, $page, $per_page);
        $projects   = $this->appendStatistics($projects, $statistics);
        return $projects;
    }

    public function byUserName($name)
    {
        $users = $this->user_repo->byName($name);
        if ($users->count() == 0) {
            return new Collection();
        }

        $projects = $this->project->with($this->with_relations)
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
            ->where(
                function ($query) use ($keys) {
                    foreach ($keys as $k) {
                        $query->orWhere('project_title', 'LIKE', "%{$k}%");
                    }
                }
            )
            ->orderBy('project_id', 'desc')->get();
    }

    public function byDateRange($dstart = '', $dend = '')
    {
        if (!$dstart and !$dend) {
            return new Collection();
        }

        $dstart = $dstart ? Carbon::parse($dstart)->startOfDay() : Carbon::now()->startOfMonth()->startOfDay();
        $dend   = $dend ? Carbon::parse($dend)->endOfDay() : Carbon::now()->endOfDay();

        return $this->project->whereBetween('update_time', [$dstart, $dend])
            ->orderBy('project_id', 'desc')
            ->get();
    }

    public function categoryOptions()
    {
        return  $this->category->categories();
    }

    public function currentStageOptions()
    {
        return $this->project->getCurrentStageOptions();
    }

    public function resourceOptions()
    {
        return $this->project->getResourceOptions();
    }

    public function quantityOptions()
    {
        return $this->project->getQuantityOptions();
    }

    public function teamSizeOptions()
    {
        return $this->project->getTeamSizeOptions();
    }

    public function budgetOptions()
    {
        return $this->project->getBudgetOptions();
    }

    public function innovationOptions()
    {
        return $this->project->getInnovationOptions();
    }

    public function projectTagTree()
    {
        return $this->project_tag_builder->projectTagTree();
    }

    public function tagTree()
    {
        return $this->tag_builder->tagTree();
    }

    /*
     * @param Paginator|Collection
     * return array
     */
    public function toOutputArray($projects)
    {
        $output = [];
        foreach ($projects as $project) {
            $output[] = $this->projectOutput($project);
        }

        return $output;
    }

    private function projectOutput(Project $project)
    {
        return [
            '#'                         => $project->project_id,
            'Title'                     => $project->project_title,
            'Category'                  => $project->textCategory(),
            'Member'                    => $project->textUserName(),
            'Country'                   => $project->project_country,
            'City'                      => $project->project_city,
            'Current Development Stage' => $project->textProgress(),
            'First Batch Quantity'      => $project->firstBatchQuantity(),
            'Target Price - MSRP'       => $project->textMsrp(),
            'Target Shipping Date'      => $project->textLaunchDate(),
            'Status'                    => $project->profile->text_status,
            'Submit'                    => $project->profile->text_project_submit,
            'Hub'                       => $project->schedule ? 'Y' : '',
            'Create Date'               => $project->date_added,
            'Last Update'               => $project->update_time,
            'Key Components List'       => implode(',', $project->keyComponents()),
            'Team Strengths'            => implode(',', $project->teamStrengths()),
            'Resource Requirements'     => implode(',', $project->resourceRequirements()),
            'Pairing Tags'              => $this->tag_builder->tagOutput($project->getProjectTagsAttribute()),
            'Brief'                     => e($project->project_summary),
        ];
    }

    public function update($project_id, $data)
    {
        $this->project_modifier->updateProject($project_id, $data);
    }

    public function delete($project)
    {
        $project->is_deleted   = 1;
        $project->deleted_date = Carbon::now()->toDateTimeString();

        return $project->save();
    }

    public function toDraft($project_id)
    {
        $this->project_modifier->toDraftProject($project_id);
    }

    public function toSubmittedPrivate($project_id)
    {
        $this->project_modifier->toSubmittedPrivateProject($project_id);
    }

    public function toSubmittedPublic($project_id)
    {
        $this->project_modifier->toSubmittedPublicProject($project_id);
    }

    public function updateNote($project_id, $data)
    {
        return $this->project_modifier->updateProjectMemo($project_id, $data);
    }

    public function updateInternalNote($project_id, $data)
    {
        return $this->project_modifier->updateProjectMemo($project_id, $data);
    }

    private function getProjectStatistics(Collection $projects)
    {
        $result = [];
        $result['public_count'] = $projects->filter(function (Project $item) {
            if ($item->profile->isPublic()) {
                return $item;
            }
        })->count();

        $result['private_count'] = $projects->filter(function (Project $item) {
            if ($item->profile->isPrivate()) {
                return $item;
            }
        })->count();

        $result['draft_count'] = $projects->filter(function (Project $item) {
            if ($item->profile->isDraft()) {
                return $item;
            }
        })->count();

        $result['delete_count'] = $projects->filter(function (Project $item) {
            if ($item->isDeleted()) {
                return $item;
            }
        })->count();

        return $result;
    }

    private function appendStatistics($object, $statistics)
    {
        foreach ($statistics as $key => $row) {
            $object->$key = $row;
        }
        return $object;
    }
}
