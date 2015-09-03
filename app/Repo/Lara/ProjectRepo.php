<?php namespace Backend\Repo\Lara;

use Carbon;
use Backend\Model\Eloquent\Project;
use Backend\Model\Eloquent\ProjectCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Model\ModelInterfaces\ProjectTagBuilderInterface;
use Backend\Model\ModelInterfaces\ProjectModifierInterface;
use Backend\Repo\RepoTrait\PaginateTrait;

class ProjectRepo implements ProjectInterface
{
    use PaginateTrait;

    protected $with_relations = ['user', 'category', 'propose'];

    public function __construct(
        Project $project,
        ProjectCategory $category,
        UserInterface $user,
        ProjectTagBuilderInterface $project_tag_builder,
        ProjectModifierInterface $project_modifier
    ) {
        $this->project  = $project;
        $this->category = $category;

        $this->user_repo           = $user;
        $this->project_tag_builder = $project_tag_builder;

        $this->project_modifier = $project_modifier;
    }

    public function find($id)
    {
        $project = $this->project->queryProject()->find($id);

        return $project;
    }

    public function all()
    {
        return $this->project->queryProject()
            ->with($this->with_relations)
            ->orderBy('project_id', 'desc')
            ->get();
    }

    public function deletedProjects()
    {
        return $this->project->queryProject()
            ->queryDeleted()
            ->orderBy('project_id', 'desc')
            ->get();
    }

    public function byPage($page = 1, $limit = 20)
    {
        $projects = $this->modelBuilder($this->project, $page, $limit)
            ->with($this->with_relations)
            ->queryProject()
            ->queryNotDeleted()
            ->get();

        $this->setPaginateTotal($this->project->queryProject()->count());

        return $this->getPaginateContainer($this->project, $page, $limit, $projects);
    }

    public function byUserId($user_id)
    {
        $projects = $this->project->with($this->with_relations)
            ->queryProject()
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
            ->queryProject()
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
            ->queryProject()
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
            ->queryProject()
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
            ->queryProject()
            ->orderBy('project_id', 'desc')
            ->get();
    }

    public function categoryOptions($is_selected = true)
    {
        return $is_selected ?
            $this->category->categories() :
            array_merge(
                $this->category->emptyOption(),
                $this->category->categories()
            );
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

    public function projectTagTree()
    {
        return $this->project_tag_builder->projectTagTree();
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
            'Pairing Tags'              => $this->project_tag_builder->projectTagOutput($project->project_tags),
            'Brief'                     => e($project->project_summary),
        ];
    }

    public function update($project_id, $data)
    {
        $this->project_modifier->updateProject($project_id, $data);
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
        $model = new Project();
        $project = $model->find($project_id);
        $project->hub_note = $data['hub_note'];
        $project->hub_note_level = $data['hub_note_level'];
        return $project->save();
    }
}
