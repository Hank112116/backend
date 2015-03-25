<?php

namespace Backend\Http\Controllers;

use Auth;
use Illuminate\Support\Collection;
use Backend\Repo\RepoInterfaces\SolutionInterface;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoInterfaces\AdminerInterface;
use Input;
use Noty;
use Redirect;
use Response;

class SolutionController extends BaseController
{

    protected $cert = 'solution';

    public function __construct(
        SolutionInterface $solution,
        ProjectInterface $project,
        AdminerInterface $adminer
    )
    {
        parent::__construct();

        $this->solution_repo = $solution;
        $this->project_repo  = $project;
        $this->adminer_repo  = $adminer;
    }

    public function showList()
    {
        $solutions = $this->solution_repo->approvedSolutions($this->page, $this->per_page);

        return $this->showSolutions($solutions);
    }

    public function showWaitApproveSolutions()
    {
        $solutions = $this->solution_repo->waitApproveSolutions();

        return $this->showSolutions($solutions, $paginate = false);
    }

    public function showDraftSolutions()
    {
        $solutions = $this->solution_repo->drafts();

        return $this->showSolutions($solutions, $paginate = false);
    }

    public function showDeletedSolutions()
    {
        $solutions = $this->solution_repo->deletedSolutions();

        return $this->showSolutions($solutions, $paginate = false, $title = 'deleted solutions');
    }

    public function showSearch($search_by)
    {
        switch ($search_by) {
            case 'name':
                $solutions = $this->solution_repo->byUserName(Input::get('name'));
                break;

            case 'title':
                $solutions = $this->solution_repo->byTitle(Input::get('title'));
                break;

            default:
                $solutions = new Collection();
        }

        if ($solutions->count() == 0) {
            Noty::warnLang('common.no-search-result');

            return Redirect::action('SolutionController@showList');
        }

        return $this->showSolutions($solutions, $paginate = false);
    }

    public function showSolutions($solutions, $paginate = true, $title = '')
    {
        if (Input::has('csv')) {
            return $this->renderCsv($solutions);
        }

        $has_wait_approve = Auth::user()->isManager() ?
            $this->solution_repo->hasWaitManagerApproveSolution() :
            $this->solution_repo->hasWaitApproveSolution();

        return view('solution.list')->with([
            'title'                      => $title ?: 'solutions',
            'per_page'                   => $paginate? $this->per_page : '',
            'is_restricted'              => $this->is_restricted_adminer,

            'solutions'                  => $solutions,
            'has_wait_approve_solutions' => $has_wait_approve,
        ]);
    }

    private function renderCsv($solutions)
    {
        $output = $this->solution_repo->toOutputArray(
            Input::get('csv') == 'all' ? $this->solution_repo->all() : $solutions
        );

        return $this->outputArrayToCsv($output, 'solutions');
    }

    /**
     *
     * @param int $solution_id
     * @return View
     **/
    public function showDetail($solution_id)
    {
        $solution = $this->solution_repo->find($solution_id);

        return view('solution.detail')->with([
            'is_restricted'    => $this->is_restricted_adminer,
            'project_tag_tree' => $this->project_repo->projectTagTree(),
            'solution'         => $solution,
        ]);
    }

    /**
     * @param int $solution_id
     * @return View
     **/
    public function showUpdate($solution_id)
    {
        $is_wait_approve_ongoing = $this->solution_repo->isWaitApproveOngoing($solution_id);

        $solution = $is_wait_approve_ongoing ?
            $this->solution_repo->findDuplicate($solution_id) : $this->solution_repo->find($solution_id);

        return view($is_wait_approve_ongoing ? 'solution.update-ongoing' : 'solution.update')
            ->with([
                'is_restricted'            => $this->is_restricted_adminer,
                'project_tag_tree'         => $this->project_repo->projectTagTree(),

                'category_options'         => $this->solution_repo->categoryOptions(),
                'certification_options'    => $this->solution_repo->certificationOptions(),

                'project_progress_options' => $this->project_repo->currentStageOptions(),
                'project_category_options' => $this->project_repo->categoryOptions(),

                'solution'                 => $solution,
            ]);
    }

    /**
     * @param int $solution_id
     * @return Response
     **/
    public function update($solution_id)
    {
        $this->solution_repo->update($solution_id, Input::all());
        Noty::successLang('solution.admin-update');

        return Redirect::action('SolutionController@showDetail', $solution_id);
    }

    /**
     * @param int $solution_id
     * @return Response
     **/
    public function updateOngoing($solution_id)
    {
        $this->solution_repo->duplicateRepo()->update($solution_id, Input::all());
        Noty::successLang('solution.admin-update-ongoing');

        return Redirect::action('SolutionController@showUpdate', $solution_id);
    }

    public function approve($solution_id)
    {
        $this->solution_repo->approve($solution_id, Auth::user()->isManager());
        Noty::successLang('solution.approve');

        return Redirect::action('SolutionController@showDetail', $solution_id);
    }

    public function reject($solution_id)
    {
        $this->solution_repo->reject($solution_id);
        Noty::successLang('solution.reject');

        return Redirect::action('SolutionController@showDetail', $solution_id);
    }

    public function approveEdition($solution_id)
    {
        $this->solution_repo->duplicateRepo()->approve($solution_id, Auth::user()->isManager());
        Noty::successLang('solution.approve');

        return Redirect::action('SolutionController@showDetail', $solution_id);
    }

    public function rejectEdition($solution_id)
    {
        $this->solution_repo->duplicateRepo()->reject($solution_id);
        Noty::successLang('solution.reject');

        return Redirect::action('SolutionController@showDetail', $solution_id);
    }

    public function onShelf($solution_id)
    {
        $this->solution_repo->onShelf($solution_id);
        Noty::successLang('solution.on-shelf');

        return Redirect::action('SolutionController@showDetail', $solution_id);
    }

    public function offShelf($solution_id)
    {
        $this->solution_repo->offShelf($solution_id);
        Noty::successLang('solution.off-shelf');

        return Redirect::action('SolutionController@showDetail', $solution_id);
    }
}
