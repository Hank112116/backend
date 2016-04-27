<?php

namespace Backend\Http\Controllers;

use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoInterfaces\HubInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Model\Plain\TagNode;
use Input;
use Noty;
use Redirect;
use Log;
use Response;

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
        $this->project_repo = $project;
        $this->adminer_repo = $adminer;
        $this->hub_repo     = $hub;
        $this->user_repo    = $user;
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
        $projects = $this->project_repo->byUnionSearch(Input::all(), $this->page, $this->per_page);
        $log_action = 'Search project';
        Log::info($log_action, Input::all());

        if ($projects->count() == 0) {
            Noty::warnLang('common.no-search-result');
        }

        return $this->showProjects($projects, $paginate = true);
    }

    public function showProjects($projects, $paginate = true, $title = '')
    {
        if (Input::has('csv')) {
            return $this->renderCsv($projects);
        }

        $hwtrek_pms = $this->user_repo->findHWTrekPM();

        $pm_ids = [];
        if ($hwtrek_pms) {
            foreach ($hwtrek_pms as $pm) {
                $pm_ids[] = $pm->user_id;
            }
        }
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
        if (Input::get('csv') == 'all') {
            $output = $this->project_repo->toOutputArray($this->project_repo->all());
        } else {
            $output = $this->project_repo->toOutputArray($projects);
        }

        $csv_type   = Input::get('csv') == 'all' ? 'all' : 'this';
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

            return Redirect::action('ProjectController@showList');
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

            return Redirect::action('ProjectController@showList');
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

        return Redirect::action('ProjectController@showDetail', $project_id);
    }

    public function update($project_id)
    {
        $log_action = 'Edit project';
        $log_data   =  [
            'project' => $project_id,
            Input::all()
        ];
        Log::info($log_action, $log_data);

        $this->project_repo->update($project_id, Input::all());
        Noty::successLang('common.update-success');

        return Redirect::action('ProjectController@showDetail', $project_id);
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

        return Redirect::action('ProjectController@showList');
    }

    public function updateMemo()
    {
        $input = Input::all();
        if ($this->project_repo->updateInternalNote($input['project_id'], $input)) {
            $res   = ['status' => 'success'];
        } else {
            $res   = ['status' => 'fail', "msg" => "Update Fail!"];
        }

        $log_action = 'Edit internal information';
        Log::info($log_action, $input);

        return Response::json($res);
    }
    
    public function updateManager()
    {
        $input = Input::all();
        if ($this->project_repo->updateProjectManager($input['project_id'], $input)) {
            $res   = ['status' => 'success'];
        } else {
            $res   = ['status' => 'fail', "msg" => "Update Fail!"];
        }

        $log_action = 'Edit internal information';
        Log::info($log_action, $input);

        return Response::json($res);
    }

    public function proposeSolution()
    {
        $project_id   = Input::get('project_id');
        $propose_type = Input::get('propose_type');
        $dstart       = Input::get('dstart');
        $dend         = Input::get('dend');

        $project = $this->project_repo->find($project_id);
        $result = $project->proposeSolutionStatistics($dstart, $dend);
        if ($propose_type == 'internal') {
            return Response::json($result->internal_data);
        } elseif ($propose_type == 'external') {
            return Response::json($result->external_data);
        } else {
            return Response::json('', 400);
        }
    }

    public function recommendExpert()
    {
        $project_id     = Input::get('project_id');
        $recommend_type = Input::get('recommend_type');
        $dstart         = Input::get('dstart');
        $dend           = Input::get('dend');
        $project = $this->project_repo->find($project_id);
        $result  = $project->recommendExpertStatistics($dstart, $dend);
        if ($recommend_type == 'internal') {
            return Response::json($result->internal_data);
        } elseif ($recommend_type == 'external') {
            return Response::json($result->external_data);
        } else {
            return Response::json('', 400);
        }
    }
}
