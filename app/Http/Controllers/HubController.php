<?php

namespace Backend\Http\Controllers;

use Backend\Repo\RepoInterfaces\HubInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Model\Eloquent\Project;
use Mews\Purifier\Purifier;
use Config;
use Input;
use Noty;
use Redirect;
use Response;

class HubController extends BaseController
{

    protected $cert = 'hub';

    public function __construct(
        HubInterface $hub,
        UserInterface $user,
        AdminerInterface $adminer,
        Purifier $purifier
    ) {
        parent::__construct();

        $this->hub_repo     = $hub;
        $this->user_repo    = $user;
        $this->adminer_repo = $adminer;
        $this->purifier     = $purifier;
    }

    /**
     * Display a listing of the resource.
     * GET /hub/questionnaires
     *
     */
    public function indexQuestionnaire()
    {
        return view('hub.questionnaires')
            ->with('questionnaires', $this->hub_repo->allQuestionnaires())
            ->with('is_restricted', $this->is_restricted_adminer);
    }

    /**
     * Display a listing of the resource.
     * GET /hub/schedules
     *
     */
    public function indexSchedule()
    {
        $front   = Config::get('app.front_domain');
        $preview = [
            'project' => "//{$front}/hub/schedule-preview/project/",
            'version' => "//{$front}/hub/schedule-preview/version/"
        ];

        return view('hub.schedules')
            ->with('schedules', $this->hub_repo->allSchedules())
            ->with('preview', $preview)
            ->with('is_restricted', $this->is_restricted_adminer);
    }

    /**
     * Display a listing of the resource.
     * GET /hub/questionnaire/detail/{id}
     *
     * @param int $id questionnaire_id
     * @return View
     */
    public function showQuestionnaireDetail($id)
    {
        $questionnaire = $this->hub_repo->findQuestionnaire($id);

        return view('hub.questionnaire-detail')
            ->with('q', $questionnaire)
            ->with('schedule', $questionnaire->schedule ?: $this->hub_repo->dummySchedule())
            ->with('user', $questionnaire->user ?: $this->user_repo->dummy())
            ->with('adminers', $this->adminer_repo->all());
    }

    /**
     * Show schedule manager update.
     * GET /hub/schedule-manager/{id}
     * @param $id
     * @return $this
     */
    public function showUpdateScheduleManager($id)
    {
        $questionnaire = $this->hub_repo->findQuestionnaire($id);

        return view('hub.update-schedule-manager')
            ->with('q', $questionnaire)
            ->with('schedule', $questionnaire->schedule)
            ->with('user', $questionnaire->user)
            ->with('adminers', $this->adminer_repo->all());
    }

    /**
     * @param $id
     * @return mixed
     */
    public function updateScheduleManager($id)
    {
        $schedule = $this->hub_repo->findSchedule($id);
        $this->hub_repo->updateScheduleManagers($schedule, Input::all());
        $projectTitle = $this->purifier->clean($schedule->project_title);
        Noty::success("Project [{$projectTitle}] managers is updated");

        return Redirect::action('HubController@indexQuestionnaire');
    }

    /**
     * Approve a project schedule
     * GET /hub/schedule/approve/{project_id}
     *
     * @param int $id project_id
     * @return Redirect
     */
    public function approveSchedule($id)
    {
        $schedule = $this->hub_repo->findSchedule($id);
        $this->hub_repo->approveSchedule($schedule);

        Noty::success("Project [{$schedule->project_title}] is approved");

        return Redirect::action('HubController@indexSchedule');
    }
    /**
     * @param $projectId $note
     * @return mixed
     */
    public function updateProjectNote()
    {
        $input = Input::all();
        $projectModle = new Project();
        $project = $projectModle->find($input["projectId"]);
        $project->hub_note = $input["note"];
        $project->hub_note_level = $input["level"];
        if ($project->save()) {
            $res   = ['status' => 'success'];
        } else {
            $res   = ['status' => 'fail', "msg" => "Update Fail!"];
        }
        return Response::json($res);
    }
    /**
     * @param $expertId
     * @return mixed
     */
    public function getExpert()
    {
        $input = Input::all();
        $expert = $this->user_repo->findExpert($input["expertId"]);

        if (sizeof($expert) >0) {
            $res   = ['msg' => "{$expert[0]->user_name} ({$expert[0]->company})"];
        } else {
            $res   = ['msg' => 'no expert'];
        }
        return Response::json($res);
    }
}
