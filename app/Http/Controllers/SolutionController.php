<?php

namespace Backend\Http\Controllers;

use Backend\Api\ApiInterfaces\SolutionApi\SolutionApiInterface;
use Backend\Assistant\ApiResponse\SolutionApi\SolutionListResponseAssistant;
use Backend\Assistant\ApiResponse\SolutionApi\SolutionResponseAssistant;
use Backend\Enums\API\Response\Key\SolutionKey;
use Backend\Model\ModelInterfaces\FeatureModifierInterface;
use Backend\Repo\RepoInterfaces\SolutionInterface;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Facades\Log;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Noty;

class SolutionController extends BaseController
{
    protected $cert = 'solution';

    private $solution_repo;
    private $project_repo;
    private $adminer_repo;
    private $solution_api;
    private $feature_modifier;

    public function __construct(
        SolutionInterface $solution,
        ProjectInterface $project,
        AdminerInterface $adminer,
        SolutionApiInterface $solution_api,
        FeatureModifierInterface $feature_modifier
    ) {
        parent::__construct();

        $this->solution_repo    = $solution;
        $this->project_repo     = $project;
        $this->adminer_repo     = $adminer;
        $this->solution_api     = $solution_api;
        $this->feature_modifier = $feature_modifier;
    }

    public function showList()
    {
        $query = [
            SolutionKey::KEY_PAGE                => $this->page,
            SolutionKey::KEY_LIMIT               => $this->per_page,
            SolutionKey::KEY_SOLUTION_ID         => empty($this->request->get('solution_id')) ? null : $this->request->get('solution_id'),
            SolutionKey::KEY_OWNER               => empty($this->request->get('user_name')) ? null : $this->request->get('user_name'),
            SolutionKey::KEY_TITLE               => empty($this->request->get('solution_title')) ? null : $this->request->get('solution_title'),
            SolutionKey::KEY_APPROVED_START_TIME => empty($this->request->get('dstart')) ? null : $this->request->get('dstart'),
            SolutionKey::KEY_APPROVED_END_TIME   => empty($this->request->get('dend')) ? null : $this->request->get('dend'),
            SolutionKey::KEY_STATUS              => $this->request->get('status') === 'all' ? null : $this->request->get('status')
        ];

        $response = $this->solution_api->listSolutions($query);

        $solution_list_assistant = SolutionListResponseAssistant::create($response);

        $solutions = $solution_list_assistant->getSolutionListPaginate($this->per_page);

        if ($solutions->count() == 0) {
            Noty::warnLang('common.no-search-result');
        }

        if ($this->request->has('csv')) {
            return $this->renderCsv($solutions);
        }

        return view('solution.list')->with([
            'is_restricted'                => $this->isRestricted(),
            'solutions'                    => $solutions,
            'has_wait_approve_solutions'   => $solution_list_assistant->hasWaitPublishSolution(),
            'total_count'                  => $solution_list_assistant->getTotalCount(),
        ]);
    }

    /**
     * @param $solutions
     * @return int
     */
    private function renderCsv($solutions)
    {
        $output = $this->solution_repo->toOutputArray($solutions);

        $log_action = 'CSV of Solution';

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
        $response = $this->solution_api->getSolution($solution_id);

        $solution_assistant = SolutionResponseAssistant::create($response);

        $solution = $solution_assistant->getSolution();

        if (is_null($solution)) {
            Noty::warnLang('solution.no-solution');
            return redirect()->action('SolutionController@showList');
        }

        return view('solution.detail')->with([
            'is_restricted'    => $this->isRestricted(),
            'solution'         => $solution,
            'image_gallery'    => json_encode($solution->getShowcase())
        ]);
    }

    /**
     * @param int $solution_id
     * @return View
     **/
    public function showUpdate($solution_id)
    {
        $response = $this->solution_api->getSolution($solution_id);

        $solution_assistant = SolutionResponseAssistant::create($response);

        $solution = $solution_assistant->getSolution();

        if (is_null($solution)) {
            Noty::warnLang('solution.no-solution');
            return redirect()->action('SolutionController@showList');
        }

        return view('solution.update')
            ->with([
                'is_restricted'  => $this->isRestricted(),
                'solution'       => $solution,
                'image_gallery'  => json_encode($solution->getShowcase()),
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

        if ($this->request->has('user_id')) {
            if ($this->request->get('user_id') == '0') {
                return redirect()->action('SolutionController@showUpdate', $solution_id);
            }
        }

        // TODO use upload solution picture API
        $image_gallery = $this->solution_repo->updateImageGalleries($solution_id, $this->request->all());

        $modify_data = [
            'ownerId'   => $this->request->get('user_id'),
            'image'     => $image_gallery['image'],
            'showcase'  => $image_gallery['image_gallery'],
        ];

        $response = $this->solution_api->modifySolution($solution_id, $modify_data);

        if ($response->isOk()) {
            Noty::successLang('solution.admin-update');
            return redirect()->action('SolutionController@showDetail', $solution_id);
        } else {
            Noty::warnLang('solution.admin-update-fail');
            return redirect()->action('SolutionController@showUpdate', $solution_id);
        }
    }

    //change solution type to program (solution table:is_program)
    public function toProgram()
    {
        if ($this->authUser()->isBackendPM() || $this->authUser()->isAdmin() || $this->authUser()->isManagerHead()) {
            $solution_id = $this->request->get('solution_id');

            if ($this->authUser()->isBackendPM()) {
                $response = $this->solution_api->changeToPendingToProgram($solution_id);

                Log::info('Solution pending to program', ['solution_id' => $solution_id]);
            } else {
                $response = $this->solution_api->changeToProgram($solution_id);

                $this->feature_modifier->solutionFeatureToProgram($solution_id);

                Log::info('Solution to program', ['solution_id' => $solution_id]);
            }
        } else {
            $response = response('', Response::HTTP_FORBIDDEN);
        }
        return $response;
    }

    //change solution type to program (solution table:is_program)
    public function toSolution()
    {
        if ($this->authUser()->isBackendPM() || $this->authUser()->isAdmin() || $this->authUser()->isManagerHead()) {
            $solution_id = $this->request->get('solution_id');

            if ($this->authUser()->isBackendPM()) {
                $response = $this->solution_api->changeToPendingToNormalSolution($solution_id);

                Log::info('Program pending to solution', ['solution_id' => $solution_id]);
            } else {
                $response = $this->solution_api->changeToNormalSolution($solution_id);

                $this->feature_modifier->programFeatureToNormalSolution($solution_id);

                Log::info('Program to solution', ['solution_id' => $solution_id]);
            }
        } else {
            $response = response('', Response::HTTP_FORBIDDEN);
        }
        return $response;
    }

    //cancel pending solution to program
    public function cancelPendingSolution()
    {
        if ($this->authUser()->isBackendPM() || $this->authUser()->isAdmin() || $this->authUser()->isManagerHead()) {
            $solution_id = $this->request->get('solution_id');

            $response = $this->solution_api->changeToProgram($solution_id);

            Log:info('Cancel pending to solution', ['solution_id' => $solution_id]);
        } else {
            $response = response('', Response::HTTP_FORBIDDEN);
        }
        return $response;
    }

    //cancel pending program to solution
    public function cancelPendingProgram()
    {
        if ($this->authUser()->isBackendPM() || $this->authUser()->isAdmin() || $this->authUser()->isManagerHead()) {
            $solution_id = $this->request->get('solution_id');

            $response = $this->solution_api->changeToNormalSolution($solution_id);

            Log:info('Cancel pending to program', ['solution_id' => $solution_id]);
        } else {
            $response = response('', Response::HTTP_FORBIDDEN);
        }
        return $response;
    }

    public function approve($solution_id)
    {
        Log::info('Solution approved', ['solution_id' => $solution_id]);

        return $this->solution_api->approve($solution_id);
    }

    public function reject($solution_id)
    {
        Log:info('Solution rejected', ['solution_id' => $solution_id]);

        return $this->solution_api->reject($solution_id);
    }

    public function onShelf($solution_id)
    {
        $response = $this->solution_api->onShelf($solution_id);

        if (!$response->isOk()) {
            Noty::warn('The solution on shelf fail.');
        }

        $log_action = 'Solution on shelf';
        $log_data   = [
            'solution_id' => $solution_id,
        ];
        Log::info($log_action, $log_data);

        Noty::successLang('solution.on-shelf');

        return redirect()->action('SolutionController@showDetail', $solution_id);
    }

    public function offShelf($solution_id)
    {
        $response = $this->solution_api->offShelf($solution_id);

        if (!$response->isOk()) {
            Noty::warn('The solution off shelf fail.');
        }

        $log_action = 'Solution off shelf';
        $log_data   = [
            'solution_id' => $solution_id,
        ];
        Log::info($log_action, $log_data);

        Noty::successLang('solution.off-shelf');

        return redirect()->action('SolutionController@showDetail', $solution_id);
    }
}
