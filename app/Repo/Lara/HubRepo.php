<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\HubQuestionnaire;
use Backend\Model\Eloquent\HubSchedule;
use Config;
use Requests;
use Backend\Repo\RepoInterfaces\HubInterface;

class HubRepo implements HubInterface
{
    public function __construct(HubQuestionnaire $q, HubSchedule $schedule)
    {
        $this->questionnaire = $q;
        $this->schedule      = $schedule;

        $this->token        = Config::get('app.hub_token');
        $this->snapshot_api = 'https://'.Config::get('app.front_domain').'/hub/apis/admin-snapshot';
    }

    public function dummySchedule()
    {
        return new HubSchedule();
    }

    public function findQuestionnaire($id)
    {
        return $this->questionnaire->with('schedule', 'user')->find($id);
    }

    public function findSchedule($id)
    {
        return $this->schedule->find($id);
    }

    public function allQuestionnaires()
    {
        return $this->questionnaire->with('schedule', 'user')
            ->orderBy('questionnaire_id', 'desc')
            ->get();
    }

    public function allSchedules()
    {
        return $this->schedule->whereNotNull('schedule')
            ->orderBy('project_id', 'desc')
            ->get();
    }

    public function approveSchedule(HubSchedule $schedule)
    {
        $schedule->hub_approve      = 1;
        $schedule->hub_approve_time = $this->recordApproveVersion($schedule);
        $schedule->save();

        return $schedule;
    }

    private function recordApproveVersion($schedule)
    {
        $header  = [];
        $data    = [
            'projectId' => $schedule->project_id,
            'token'     => md5("{$schedule->project_id}_{$this->token}"),
        ];
        $options = ['verify' => false, 'verifyname' => false];

        $result = Requests::post($this->snapshot_api, $header, $data, $options);
        if (!$result->success) {
            return '0000-00-00';
        }

        $body = json_decode($result->body, true);
        $version_id = $body['data']['version_id'];

        $version = \DB::table('pms_schedule_version')
            ->select('create_time')
            ->where('version_id', $version_id)
            ->first();

        return $version->create_time;
    }

    // update project hub_managers
    public function updateScheduleManagers(HubSchedule $schedule, $data)
    {
        $managers               = array_get($data, 'managers', []);
        $schedule->hub_managers = implode(',', $managers);
        $schedule->save();
    }
}
