<?php namespace Backend\Repo\Lara;

use Carbon;
use Backend\Model\Eloquent\Project;
use Backend\Model\Eloquent\ProjectCategory;
use Backend\Model\Eloquent\ProposeSolution;
use Backend\Model\Eloquent\GroupMemberApplicant;
use Backend\Model\Eloquent\ProjectMailExpert;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Model\ModelInterfaces\TagBuilderInterface;
use Backend\Model\ModelInterfaces\ProjectTagBuilderInterface;
use Backend\Model\ModelInterfaces\ProjectModifierInterface;
use Backend\Repo\RepoTrait\PaginateTrait;
use Illuminate\Database\Eloquent\Collection;

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
    private $propose_solution;
    private $group_member_applicant;
    private $project_mail_expert;

    public function __construct(
        AdminerInterface $adminer,
        Project $project,
        ProjectCategory $category,
        UserInterface $user,
        TagBuilderInterface $tag_builder,
        ProjectTagBuilderInterface $project_tag_builder,
        ProjectModifierInterface $project_modifier,
        ProposeSolution $propose_solution,
        GroupMemberApplicant $group_member_applicant,
        ProjectMailExpert $project_mail_expert
    ) {
        $this->adminer                = $adminer;
        $this->project                = $project;
        $this->category               = $category;
        $this->user_repo              = $user;
        $this->tag_builder            = $tag_builder;
        $this->project_tag_builder    = $project_tag_builder;
        $this->project_modifier       = $project_modifier;
        $this->propose_solution       = $propose_solution;
        $this->group_member_applicant = $group_member_applicant;
        $this->project_mail_expert    = $project_mail_expert;
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

    public function byUnionSearch($input, $page, $per_page, $do_statistics = false)
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
        $search_projects = $projects;
        $projects        = $this->getPaginateFromCollection($projects, $page, $per_page);

        if ($do_statistics) {
            $statistics = $this->getProjectStatistics($search_projects);
            $match          = $this->getProjectMatchFromPM($search_projects, $input['dstart'], $input['dend']);
            $user_referrals = $this->getUserReferralsTotal($search_projects, $input['dstart'], $input['dend']);
            $projects       = $this->appendStatistics($projects, $statistics);
            $projects->match_statistics = $match;
            $projects->user_referrals = $user_referrals;
        }
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

    public function updateProjectManager($project_id, $data)
    {
        return $this->project_modifier->updateProjectManager($project_id, $data);
    }

    private function getProjectMatchFromPM($projects, $dstart = null, $dend = null)
    {
        // end day add one day
        if ($dend) {
            $dend = Carbon::parse($dend)->addDay()->toDateString();
        }

        $result          = [];
        $project_ids     = [];
        $group_ids       = [];
        $recommend_total = 0;
        $propose_total   = 0;
        // find pm, project id, project group id
        $pms         = $this->user_repo->findHWTrekPM();
        if ($projects) {
            foreach ($projects as $project) {
                $project_ids[] = $project->project_id;
                if ($project->group) {
                    foreach ($project->group as $group) {
                        $group_ids[] = $group->group_id;
                    }
                }
            }
        }

        if ($pms) {
            foreach ($pms as $pm) {
                $propose_model   = $this->propose_solution;
                $applicant_model = $this->group_member_applicant;
                $email_out_model = $this->project_mail_expert;
                $data = [];
                $statistics_project_ids = [];

                $propose_solutions = $propose_model
                    ->where('proposer_id', $pm->user_id)
                    ->whereIn('project_id', $project_ids)
                    ->where('event', '!=', 'click');
                if ($dstart) {
                    $propose_solutions->where('propose_time', '>=', $dstart);
                }
                if ($dend) {
                    $propose_solutions->where('propose_time', '<=', $dend);
                }

                $propose_solutions = $propose_solutions->get();
                $propose_count     = $propose_solutions->count();
                if ($propose_solutions) {
                    foreach ($propose_solutions as $solution) {
                        $statistics_project_ids[] = $solution->project_id;
                    }
                }

                $recommend_count = 0;
                $applicants = $applicant_model
                    ->where('referral', $pm->user_id)
                    ->whereIn('group_id', $group_ids);
                if ($dstart) {
                    $applicants->where('apply_date', '>=', $dstart);
                }
                if ($dend) {
                    $applicants->where('apply_date', '<=', $dend);
                }
                $applicants = $applicants->get();

                if ($applicants) {
                    foreach ($applicants as $applicant) {
                        if ($applicant->isRecommendExpert()) {
                            $statistics_project_ids[] = $applicant->getAppliedProjectId();
                            $recommend_count ++;
                        }
                    }
                }

                $admin = $this->adminer->findHWTrekMember($pm->user_id);
                if ($admin) {
                    $email_out = $email_out_model
                        ->where('admin_id', $admin->id)
                        ->whereIn('project_id', $project_ids);
                    if ($dstart) {
                        $email_out->where('date_send', '>=', $dstart);
                    }
                    if ($dend) {
                        $email_out->where('date_send', '<=', $dend);
                    }
                    $email_out = $email_out->get();
                    if ($email_out) {
                        foreach ($email_out as $item) {
                            $statistics_project_ids[] = $item->project_id;
                        }
                    }
                    $recommend_count = $recommend_count + count($email_out);
                }
                $user_name = \UrlFilter::filterNoHyphen($pm->user_name);
                $data['propose_count']      = $propose_count;
                $data['recommend_count']    = $recommend_count;
                $data['project_count']      = count(array_unique($statistics_project_ids));
                $data['total_count']        = $propose_count + $recommend_count;
                $result['item'][$user_name] = $data;
                $propose_total   = $propose_total + $propose_count;
                $recommend_total = $recommend_total + $recommend_count;
            }
            $result['propose_total']   = $propose_total;
            $result['recommend_total'] = $recommend_total;
        }
        return $result;
    }

    private function getUserReferralsTotal($projects, $dstart = null, $dend = null)
    {
        // end day add one day
        if ($dend) {
            $dend = Carbon::parse($dend)->addDay()->toDateString();
        }

        $group_ids       = [];
        // find pm, project id, project group id
        if ($projects) {
            foreach ($projects as $project) {
                if ($project->group) {
                    foreach ($project->group as $group) {
                        $group_ids[] = $group->group_id;
                    }
                }
            }
        }

        $applicant_model = new GroupMemberApplicant();
        $applicants = $applicant_model->whereIn('group_id', $group_ids)
        ->whereIn('event', [GroupMemberApplicant::APPLY_USER, GroupMemberApplicant::REFERRAL_USER]);
        if ($dstart) {
            $applicants->where('apply_date', '>=', $dstart);
        }
        if ($dend) {
            $applicants->where('apply_date', '<=', $dend);
        }

        $applicants = $applicants->get();


        $applicants = $applicants->filter(function (GroupMemberApplicant $item) {
            if ($item->user) {
                if ($item->user->isExpert()) {
                    return $item;
                }
            }
        });
        return $applicants->count();
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
