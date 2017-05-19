<?php

namespace Backend\Http\Controllers;

use Backend\Api\ApiInterfaces\ProjectApi\ProjectApiInterface;
use Backend\Assistant\ApiResponse\ProjectApi\ProjectListResponseAssistant;
use Backend\Assistant\ApiResponse\ProjectApi\ProjectResponseAssistant;
use Backend\Assistant\SearchAssistant;
use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Repo\RepoInterfaces\GroupMemberApplicantInterface;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoInterfaces\HubInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Model\Plain\TagNode;
use Backend\Facades\Log;
use Noty;

class ProjectController extends BaseController
{
    protected $cert = 'project';

    private $project_repo;
    private $adminer_repo;
    private $hub_repo;
    private $user_repo;
    private $applicant_repo;
    private $project_api;

    public function __construct(
        ProjectInterface $project,
        AdminerInterface $adminer,
        HubInterface $hub,
        UserInterface $user,
        GroupMemberApplicantInterface $applicant_repo,
        ProjectApiInterface $project_api
    ) {
        parent::__construct();
        $this->project_repo      = $project;
        $this->adminer_repo      = $adminer;
        $this->hub_repo          = $hub;
        $this->user_repo         = $user;
        $this->applicant_repo    = $applicant_repo;
        $this->project_api       = $project_api;
    }

    public function showList()
    {
        $query = SearchAssistant::projectSearchQuery($this->request);

        $response  = $this->project_api->listProjects($query);

        $assistant = ProjectListResponseAssistant::create($response);

        $projects = $assistant->getProjectListPaginate($this->per_page);

        if ($projects->count() == 0) {
            Noty::warnLang('common.no-search-result');
        }

        $hwtrek_pms = $this->user_repo->findHWTrekPM();

        $pm_ids = [];
        if ($hwtrek_pms) {
            foreach ($hwtrek_pms as $pm) {
                $pm_ids[] = $pm->user_id;
            }
        }

        return view('project.list')->with([
            'projects'                => $projects,
            'per_page'                => $this->per_page,
            'adminers'                => $this->adminer_repo->all(),
            'pm_ids'                  => $pm_ids,
            'not_yet_email_out_count' => $assistant->getNotYetEmailOUtCount(),
        ]);
    }

    public function csv()
    {
        $output = $this->project_repo->toOutputArray($this->project_repo->all());

        $log_action = 'CSV of Project'
        ;
        Log::info($log_action);

        return $this->outputArrayToCsv($output, 'projects');
    }

    public function showDetail($project_id)
    {
        $project    = $this->project_repo->find($project_id);
        $schedule   = $this->hub_repo->findSchedule($project_id);
        if (!$project) {
            Noty::warnLang('project.no-project');

            return redirect()->action('ProjectController@showList');
        }
        return view('project.detail')
            ->with([
                'project_tag_tree' => $this->project_repo->tagTree(),
                'project'          => $project,
                'adminers'         => $this->adminer_repo->all(),
                'schedule'         => $schedule ?: $this->hub_repo->dummySchedule()
            ]);
    }

    public function showUpdate($project_id)
    {
        $project = $this->project_repo->find($project_id);

        if (!$project) {
            Noty::warnLang('project.no-project');

            return redirect()->action('ProjectController@showList');
        }

        return view('project.update')
            ->with([
                'project_tag_tree'      => $this->project_repo->tagTree(),

                'category_options'      => $this->project_repo->categoryOptions(),
                'current_stage_options' => $this->project_repo->currentStageOptions(),
                'innovation_options'    => $this->project_repo->innovationOptions(),
                'resource_options'      => $this->project_repo->resourceOptions(),
                'quantity_options'      => $this->project_repo->quantityOptions(),
                'budget_options'        => $this->project_repo->budgetOptions(),
                'team_size_options'     => $this->project_repo->teamSizeOptions(),
                'project'               => $project
            ]);
    }

    public function updateStatus($status, $project_id)
    {
        switch ($status) {
            case 'draft':
                $this->project_repo->toDraft($project_id);
                break;

            case 'private':
                $this->project_repo->toSubmittedPrivate($project_id);
                break;

            case 'public':
                $this->project_repo->toSubmittedPublic($project_id);
                break;
        }

        $log_action = 'Update status';
        $log_data   = [
            'project' => $project_id,
            'status'  => $status
        ];
        Log::info($log_action, $log_data);

        Noty::successLang('common.update-success');

        return redirect()->action('ProjectController@showDetail', $project_id);
    }

    public function update($project_id)
    {
        $log_action = 'Edit project';
        $log_data   =  [
            'project' => $project_id,
            $this->request->all()
        ];
        Log::info($log_action, $log_data);

        $this->project_repo->update($project_id, $this->request->all());
        Noty::successLang('common.update-success');

        return redirect()->action('ProjectController@showDetail', $project_id);
    }


    public function delete($project_id)
    {
        $project = $this->project_repo->find($project_id);
        $this->project_repo->delete($project);

        Noty::success("Delete Project #{$project_id} successful");

        $log_action = 'Delete project';
        $log_data   = [
            'project' => $project_id
        ];
        Log::info($log_action, $log_data);

        return redirect()->action('ProjectController@showList');
    }

    public function updateMemo()
    {
        $input = $this->request->all();

        $project = $this->project_repo->find($input['project_id']);

        $response = $this->project_api->updateMemo($project, $input);

        if ($response->isOk()) {
            $assistant = ProjectResponseAssistant::create($response);

            // make project row view
            $view = view()->make('project.row')->with(
                [
                    'project' => $assistant->getBasicProject(),
                ]
            )->render();

            $log_action = 'Edit internal project memo';
            Log::info($log_action, $input);

            $res  = ['status' => 'success', 'view' => $view];

            return response()->json($res);
        } else {
            return $response;
        }
    }
    
    public function updateManager()
    {
        $input = $this->request->all();

        $pms     = json_decode($input['project_managers'], true);
        $project = $this->project_repo->find($input['project_id']);

        $response = $this->project_api->assignPM($project, $pms);

        if ($response->isOk()) {
            $assistant = ProjectResponseAssistant::create($response);

            // make project row view
            $view = view()->make('project.row')->with(
                [
                    'project' => $assistant->getBasicProject()
                ]
            )->render();

            $log_action = 'Edit project manager';
            Log::info($log_action, $input);

            $res  = ['status' => 'success', 'view' => $view];

            return response()->json($res);
        } else {
            return $response;
        }
    }

    /**
     * Approve a project schedule
     * GET /hub/schedule/approve/{project_id}
     *
     * @param int $id project_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveSchedule()
    {
        $id = $this->request->get('project_id');

        $project = $this->project_repo->find($id);
        if ($project->isDeleted()) {
            Noty::warn('Permission deny');
            $res   = ['status' => 'fail', 'msg' => 'Permission deny'];
            return response()->json($res);
        }

        $response = $this->project_api->releaseSchedule($project);

        if (!$response->isOk()) {
            $res   = ['status' => 'fail', 'msg' => 'Approve schedule fail.'];
            return response()->json($res);
        }

        $assistant = ProjectResponseAssistant::create($response);

        $log_action = 'release schedule';
        $log_data   = [
            'project_id' => $id
        ];
        Log::info($log_action, $log_data);

        // make project row view
        $view = view()->make('project.row')->with(
            [
                'project' => $assistant->getBasicProject()

            ]
        )->render();
        
        $res  = ['status' => 'success', 'view' => $view];

        return response()->json($res);
    }

    /**
     * PM recommend two expert for project owner (send email)
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function staffRecommendExperts()
    {
        if (empty($this->request->session()->get('admin'))) {
            $res   = ['status' => 'fail', 'msg' => 'Permissions denied'];
            return response()->json($res);
        }

        $input   = $this->request->all();
        $expert1 = $this->user_repo->findExpert($input['expert1']);
        $expert2 = $this->user_repo->findExpert($input['expert2']);

        if (empty($expert1)  || empty($expert2)) {
            $res   = ['status' => 'fail', 'msg' => 'Error expert id!'];
            return response()->json($res);
        }

        if ($expert1 === $expert2) {
            $res   = ['status' => 'fail', 'msg' => 'Duplicate expert.'];
            return response()->json($res);
        }

        $experts[] = $input['expert1'];
        $experts[] = $input['expert2'];

        $project = $this->project_repo->find($input['projectId']);

        $log_action = 'Send mail of recommend experts';
        $log_data   = [
            'project_id' => $project->project_id,
            'recommend_experts' => [
                $input['expert1'],
                $input['expert2']
            ]
        ];
        Log::info($log_action, $log_data);

        $response = $this->project_api->staffRecommendExperts($project, $experts, $this->request->session()->get('admin'));

        if ($response->getStatusCode() != \Illuminate\Http\Response::HTTP_OK) {
            return $response;
        }

        $date = new \DateTime();
        foreach ($experts as $expert) {
            $data['expert_id']  = $expert;
            $data['project_id'] = $input['projectId'];
            $data['admin_id']   = $this->request->session()->get('admin');
            $data['date_send']  = $date;
            $this->applicant_repo->insertItem($data);
        }

        // TODO user project entity
        $project = $this->project_repo->find($input['projectId']);

        $view = view()->make('project.row')->with(['project' => $project, 'tag_tree' => TagNode::tags()])->render();
        $res   = ['status' => 'success', 'view'=> $view];

        return response()->json($res);
    }

    /**
     * @param $expertId
     * @return mixed
     */
    public function getExpert()
    {
        $input  = $this->request->all();
        $expert = $this->user_repo->findExpert($input["expertId"]);
        if ($expert) {
            $res   = ['msg' => "{$expert->user_name} ({$expert->company})"];
        } else {
            $res   = ['msg' => 'no expert'];
        }
        return response()->json($res);
    }
}
