<?php

namespace Backend\Http\Controllers;

use Backend\Api\ApiInterfaces\SolutionApi\ApproveApiInterface;
use Backend\Repo\RepoInterfaces\SolutionInterface;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Facades\Log;
use Illuminate\Contracts\View\View;
use Noty;

class SolutionController extends BaseController
{
    protected $cert = 'solution';

    private $solution_repo;
    private $project_repo;
    private $adminer_repo;

    public function __construct(
        SolutionInterface $solution,
        ProjectInterface $project,
        AdminerInterface $adminer
    ) {
        parent::__construct();

        $this->solution_repo = $solution;
        $this->project_repo  = $project;
        $this->adminer_repo  = $adminer;
    }

    public function showList()
    {
        $solutions = $this->solution_repo->byPage($this->page, $this->per_page);

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

    public function showProgram()
    {
        $solutions = $this->solution_repo->program();

        return $this->showSolutions($solutions, $paginate = false);
    }

    public function showPendingProgram()
    {
        $solutions = $this->solution_repo->pendingProgram();

        return $this->showSolutions($solutions, $paginate = false);
    }

    public function showPendingSolution()
    {
        $solutions = $this->solution_repo->pendingSolution();

        return $this->showSolutions($solutions, $paginate = false);
    }

    public function showSearch()
    {
        $solution   = $this->solution_repo->byUnionSearch($this->request->all(), $this->page, $this->per_page);
        $log_action = 'Search solution';
        Log::info($log_action, $this->request->all());

        if ($solution->count() == 0) {
            Noty::warnLang('common.no-search-result');
        }

        return $this->showSolutions($solution, $paginate = true);
    }

    public function showSolutions($solutions, $paginate = true, $title = '')
    {
        if ($this->request->has('csv')) {
            return $this->renderCsv($solutions);
        }
        $has_wait_approve = auth()->user()->isBackendPM() ?
            $this->solution_repo->hasWaitManagerApproveSolution() :
            $this->solution_repo->hasWaitApproveSolution();

        $has_program = $this->solution_repo->hasProgram();
        $has_pending_up_program = $this->solution_repo->hasPendingProgram();
        $has_pending_change_solution = $this->solution_repo->hasPendingSolution();

        return view('solution.list')->with([
            'title'                        => $title ?: 'solutions',
            'per_page'                     => $paginate? $this->per_page : '',
            'is_restricted'                => $this->is_restricted_adminer,
            'solutions'                    => $solutions,
            'has_wait_approve_solutions'   => $has_wait_approve,
            'has_program'                  => $has_program,
            'has_pending_up_program'       => $has_pending_up_program,
            'has_pending_change_solution'  => $has_pending_change_solution
        ]);
    }

    private function renderCsv($solutions)
    {
        $output = $this->solution_repo->toOutputArray(
            $this->request->get('csv') == 'all' ? $this->solution_repo->all() : $solutions
        );

        $csv_type   = $this->request->get('csv') == 'all' ? 'all' : 'this';
        $log_action = 'CSV of Solution ('.$csv_type.')';
        Log::info($log_action);

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
        if (is_null($solution)) {
            Noty::warnLang('user.no-user');
            return redirect()->action('SolutionController@showList');
        }

        if ($solution->image_gallery) {
            $image_gallery = json_decode($solution->image_gallery);
            $image_gallery = str_replace('\n', '', json_encode($image_gallery, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_PRESERVE_ZERO_FRACTION));
        } else {
            $image_gallery = null;
        }

        return view('solution.detail')->with([
            'is_restricted'    => $this->is_restricted_adminer,
            'project_tag_tree' => $this->project_repo->projectTagTree(),
            'solution'         => $solution,
            'image_gallery'    => $image_gallery
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

        if (is_null($solution)) {
            Noty::warnLang('user.no-user');
            return redirect()->action('SolutionController@showList');
        }

        if ($solution->image_gallery) {
            $image_gallery = json_decode($solution->image_gallery);
            $image_gallery = str_replace('\n', '', json_encode($image_gallery, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_PRESERVE_ZERO_FRACTION));
        } else {
            $image_gallery = null;
        }

        return view($is_wait_approve_ongoing ? 'solution.update-ongoing' : 'solution.update')
            ->with([
                'is_restricted'            => $this->is_restricted_adminer,
                'project_tag_tree'         => $this->project_repo->projectTagTree(),

                'category_options'         => $this->solution_repo->categoryOptions(),
                'certification_options'    => $this->solution_repo->certificationOptions(),

                'project_progress_options' => $this->project_repo->currentStageOptions(),
                'project_category_options' => $this->project_repo->categoryOptions(),

                'solution'                 => $solution,
                'image_gallery'            => $image_gallery,
            ]);
    }

    /**
     * @param int $solution_id
     * @return Response
     **/
    public function update($solution_id)
    {
        $log_action = 'Edit solution';
        $log_data   = [
            'solution' => $solution_id,
            $this->request->all()
        ];
        Log::info($log_action, $log_data);

        $this->solution_repo->update($solution_id, $this->request->all());
        Noty::successLang('solution.admin-update');

        return redirect()->action('SolutionController@showDetail', $solution_id);
    }

    /**
     * @param int $solution_id
     * @return Response
     **/
    public function updateOngoing($solution_id)
    {
        $this->solution_repo->duplicateRepo()->update($solution_id, $this->request->all());
        Noty::successLang('solution.admin-update-ongoing');

        return redirect()->action('SolutionController@showUpdate', $solution_id);
    }

    public function approve($solution_id)
    {
        $solution = $this->solution_repo->find($solution_id);
        $approve_api = app()->make(ApproveApiInterface::class, ['solution' => $solution]);
        return $approve_api->approve();
    }

    //change solution type to program (solution table:is_program)
    public function toProgram()
    {
        if (auth()->user()->isBackendPM() || auth()->user()->isAdmin() || auth()->user()->isManagerHead()) {
            $solution_id = $this->request->get('solution_id');
            $solution = $this->solution_repo->find($solution_id);
            if ($solution) {
                $this->solution_repo->toProgram($solution_id, auth()->user()->isBackendPM());
                $res   = ['status' => 'success'];
            } else {
                $res   = ['status' => 'fail', 'msg'=>'Not found solution id!'];
            }
        } else {
            $res   = ['status' => 'fail', 'msg'=>'Permissions denied!'];
        }
        return response()->json($res);
    }
    //change solution type to program (solution table:is_program)
    public function toSolution()
    {
        if (auth()->user()->isBackendPM() || auth()->user()->isAdmin() || auth()->user()->isManagerHead()) {
            $solution_id = $this->request->get('solution_id');
            $solution = $this->solution_repo->find($solution_id);
            if ($solution) {
                $this->solution_repo->toSolution($solution_id, auth()->user()->isBackendPM());
                $res   = ['status' => 'success'];
            } else {
                $res   = ['status' => 'fail', 'msg'=>'Not found solution id!'];
            }
        } else {
            $res   = ['status' => 'fail', 'msg'=>'Permissions denied!'];
        }
        return response()->json($res);
    }

    //cancel pending solution to program
    public function cancelPendingSolution()
    {
        if (auth()->user()->isBackendPM() || auth()->user()->isAdmin() || auth()->user()->isManagerHead()) {
            $solution_id = $this->request->get('solution_id');
            $solution = $this->solution_repo->find($solution_id);
            if ($solution) {
                $this->solution_repo->toProgram($solution_id, false);
                $res   = ['status' => 'success'];
            } else {
                $res   = ['status' => 'fail', 'msg'=>'Not found solution id!'];
            }
        } else {
            $res   = ['status' => 'fail', 'msg'=>'Permissions denied!'];
        }
        return response()->json($res);
    }

    //cancel pending program to solution
    public function cancelPendingProgram()
    {
        if (auth()->user()->isBackendPM() || auth()->user()->isAdmin() || auth()->user()->isManagerHead()) {
            $solution_id = $this->request->get('solution_id');
            $solution = $this->solution_repo->find($solution_id);
            if ($solution) {
                $this->solution_repo->toSolution($solution_id, false);
                $res   = ['status' => 'success'];
            } else {
                $res   = ['status' => 'fail', 'msg'=>'Not found solution id!'];
            }
        } else {
            $res   = ['status' => 'fail', 'msg'=>'Permissions denied!'];
        }
        return response()->json($res);
    }

    public function reject($solution_id)
    {
        $solution = $this->solution_repo->find($solution_id);
        $approve_api = app()->make(ApproveApiInterface::class, ['solution' => $solution]);

        return $approve_api->reject();
    }

    public function approveEdition($solution_id)
    {
        $this->solution_repo->duplicateRepo()->approve($solution_id, auth()->user()->isBackendPM());
        Noty::successLang('solution.approve');

        return redirect()->action('SolutionController@showDetail', $solution_id);
    }

    public function rejectEdition($solution_id)
    {
        $this->solution_repo->duplicateRepo()->reject($solution_id);
        Noty::successLang('solution.reject');

        return redirect()->action('SolutionController@showDetail', $solution_id);
    }

    public function onShelf($solution_id)
    {
        $log_action = 'Solution on shelf';
        $log_data   = [
            'solution' => $solution_id,
        ];
        Log::info($log_action, $log_data);

        $this->solution_repo->onShelf($solution_id);
        Noty::successLang('solution.on-shelf');

        return redirect()->action('SolutionController@showDetail', $solution_id);
    }

    public function offShelf($solution_id)
    {
        $log_action = 'Solution off shelf';
        $log_data   = [
            'solution' => $solution_id,
        ];
        Log::info($log_action, $log_data);

        $this->solution_repo->offShelf($solution_id);
        Noty::successLang('solution.off-shelf');

        return redirect()->action('SolutionController@showDetail', $solution_id);
    }
}
