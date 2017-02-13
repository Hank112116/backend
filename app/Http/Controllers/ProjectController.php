<?php

namespace Backend\Http\Controllers;

use Backend\Api\ApiInterfaces\ProjectApi\ReleaseApiInterface;
use Backend\Repo\RepoInterfaces\AdminerInterface;
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

    public function __construct(
        ProjectInterface $project,
        AdminerInterface $adminer,
        HubInterface $hub,
        UserInterface $user
    ) {
        parent::__construct();
        $this->project_repo      = $project;
        $this->adminer_repo      = $adminer;
        $this->hub_repo          = $hub;
        $this->user_repo         = $user;
        $this->per_page          = 100;
    }

    public function showList()
    {
        $projects = $this->project_repo->byPage($this->page, $this->per_page);
        return $this->showProjects($projects);
    }

    public function showDeletedProjects()
    {
        $projects = $this->project_repo->deletedProjects();

        return $this->showProjects($projects, $paginate = false, $title = 'Deleted Projects');
    }

    public function showSearch()
    {
        $projects = $this->project_repo->byUnionSearch($this->request->all(), $this->page, $this->per_page);
        $log_action = 'Search project';
        Log::info($log_action, $this->request->all());

        if ($projects->count() == 0) {
            Noty::warnLang('common.no-search-result');
        }

        return $this->showProjects($projects, $paginate = true);
    }

    public function showProjects($projects, $paginate = true, $title = '')
    {
        if ($this->request->has('csv')) {
            return $this->renderCsv($projects);
        }

        $hwtrek_pms = $this->user_repo->findHWTrekPM();

        $pm_ids = [];
        if ($hwtrek_pms) {
            foreach ($hwtrek_pms as $pm) {
                $pm_ids[] = $pm->user_id;
            }
        }

        $projects->not_recommend_count = $this->project_repo->getNotRecommendExpertProjectCount();

        return view('project.list')
            ->with([
                'title'            => $title ?: 'projects',
                'projects'         => $projects,
                'per_page'         => $this->per_page,
                'show_paginate'    => $paginate,
                'adminers'         => $this->adminer_repo->all(),
                'tag_tree'         => TagNode::tags(),
                'pm_ids'           => $pm_ids
            ]);
    }

    private function renderCsv($projects)
    {
        if ($this->request->get('csv') == 'all') {
            $output = $this->project_repo->toOutputArray($this->project_repo->all());
        } else {
            $output = $this->project_repo->toOutputArray($projects);
        }

        $csv_type   = $this->request->get('csv') == 'all' ? 'all' : 'this';
        $log_action = 'CSV of Project ('.$csv_type.')';
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
        if ($this->project_repo->updateInternalNote($input['project_id'], $input)) {
            $project = $this->project_repo->find($input['project_id']);

            if ($input['route_path'] === 'report/project') {
                // make report project row view
                $view = view()->make('report.project-row')
                    ->with(['project' => $project, 'input' => $input, 'user_referral_total' => 0])
                    ->render();
            } else {
                // make project row view
                $view = view()->make('project.row')->with(
                    [
                        'project'       => $project,
                        'tag_tree'      => TagNode::tags()
                    ]
                )->render();
            }
            $res  = ['status' => 'success', 'view' => $view];
        } else {
            $res   = ['status' => 'fail', "msg" => "Update Fail!"];
        }

        $log_action = 'Edit internal project memo';
        Log::info($log_action, $input);

        return response()->json($res);
    }
    
    public function updateManager()
    {
        $input = $this->request->all();
        if ($this->project_repo->updateProjectManager($input['project_id'], $input)) {
            $project       = $this->project_repo->find($input['project_id']);
            // make project row view
            $view = view()->make('project.row')->with(
                [
                    'project'       => $project,
                    'tag_tree'      => TagNode::tags()
                ]
            )->render();
            $res  = ['status' => 'success', 'view' => $view];
        } else {
            $res   = ['status' => 'fail', "msg" => "Update Fail! At least one frontend and one backend PM."];
        }

        $log_action = 'Edit project manager';
        Log::info($log_action, $input);

        return response()->json($res);
    }

    /**
     * TODO remove
     */
    public function proposeSolution()
    {
        $project_id   = $this->request->get('project_id');
        $dstart       = $this->request->get('dstart');
        $dend         = $this->request->get('dend');

        $project    = $this->project_repo->find($project_id);
        $statistics = $project->proposeSolutionStatistics($dstart, $dend);
        $result['staff_propose'] = $statistics->internal_data;
        $result['user_propose']  = $statistics->external_data;
        return response()->json($result);
    }

    /**
     * TODO remove
     */
    public function recommendExpert()
    {
        $project_id     = $this->request->get('project_id');
        $dstart         = $this->request->get('dstart');
        $dend           = $this->request->get('dend');
        $project     = $this->project_repo->find($project_id);
        $statistics  = $project->recommendExpertStatistics($dstart, $dend);
        $result['staff_referral'] = $statistics->internal_data;
        $result['user_referral']  = $statistics->external_data;
        return response()->json($result);
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
        /* @var ReleaseApiInterface $release_api*/
        $release_api = app()->make(ReleaseApiInterface::class);

        $response = $release_api->releaseSchedule($project);

        if ($response->getStatusCode() != \Illuminate\Http\Response::HTTP_NO_CONTENT) {
            $error_message = json_decode($response->getContent());
            $res   = ['status' => 'fail', 'msg' => $error_message->error->message];
            return response()->json($res);
        }

        $project->hub_approve = true;
        $log_action = 'release schedule';
        $log_data   = [
            'project_id' => $id
        ];
        Log::info($log_action, $log_data);

        // make project row view
        $view = view()->make('project.row')->with(
            [
                'project'       => $project,
                'tag_tree'      => TagNode::tags()
            ]
        )->render();
        
        $res  = ['status' => 'success', 'view' => $view];

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
