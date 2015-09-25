<?php

namespace Backend\Http\Controllers;

use Illuminate\Support\Collection;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Input;
use Noty;
use Redirect;
use Log;

class ProjectController extends BaseController
{

    protected $cert = 'project';

    public function __construct(ProjectInterface $project)
    {
        parent::__construct();
        $this->project_repo = $project;
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

    public function showSearch($search_by)
    {
        switch ($search_by) {
            case 'name':
                $projects = $this->project_repo->byUserName(Input::get('name'));
                break;

            case 'project_id':
                $projects = $this->project_repo->byProjectId(Input::get('project_id'));
                break;

            case 'title':
                $projects = $this->project_repo->byTitle(Input::get('title'));
                break;

            case 'date':
                $projects = $this->project_repo->byDateRange(Input::get('dstart'), Input::get('dend'));
                break;

            default:
                $projects = new Collection();
        }

        $log_action = 'Search by '.$search_by;
        $log_data   = [
            'id'        => Input::get('project_id'),
            'user_name' => Input::get('name'),
            'title'     => Input::get('title'),
            'data'      => Input::get('dstart') ? Input::get('dstart').'~'.Input::get('dend') : null,
            'result'    => sizeof($projects)
        ];
        Log::info($log_action, $log_data);

        if ($projects->count() == 0) {
            Noty::warnLang('common.no-search-result');

            return Redirect::action('ProjectController@showList');
        } else {
            return $this->showProjects($projects, $paginate = false);
        }
    }

    public function showProjects($projects, $paginate = true, $title = '')
    {
        if (Input::has('csv')) {
            return $this->renderCsv($projects);
        }
        return view('project.list')
            ->with([
                'title'         => $title ?: 'projects',
                'projects'      => $projects,
                'per_page'      => $this->per_page,
                'show_paginate' => $paginate,
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
        $project = $this->project_repo->find($project_id);

        if (!$project) {
            Noty::warnLang('project.no-project');

            return Redirect::action('ProjectController@showList');
        }

        return view('project.detail')
            ->with([
                'project_tag_tree' => $this->project_repo->projectTagTree(),
                'project'          => $project
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
                'project_tag_tree'      => $this->project_repo->projectTagTree(),

                'category_options'      => $this->project_repo->categoryOptions(
                    $is_selected = $project->category ? true : false
                ),
                'current_stage_options' => $this->project_repo->currentStageOptions(),
                'resource_options'      => $this->project_repo->resourceOptions(),
                'quantity_options'      => $this->project_repo->quantityOptions(),

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
            'project' => $project_id
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
}
