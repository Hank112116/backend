<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\HubQuestionnaire;
use Backend\Model\Eloquent\HubSchedule;
use Config;
use Requests;
use Backend\Repo\RepoInterfaces\HubInterface;
use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Carbon;

class HubRepo implements HubInterface
{
    public function __construct(
        HubQuestionnaire $q,
        HubSchedule $schedule,
        AdminerInterface $admin,
        UserInterface $user
    ) {
        $this->questionnaire    = $q;
        $this->schedule         = $schedule;
        $this->admin            = $admin;
        $this->user            = $user;

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
        $allQuestionnaires = $this->questionnaire->with('schedule', 'user', "projectMailExpert")
            ->orderBy('questionnaire_id', 'desc')
            ->get();
        foreach ($allQuestionnaires as $questionnaire) {
            $experts = [];
            $dateSend = false;
            $adminName = false;
            $i = 0;
            foreach ($questionnaire->projectMailExpert as $projectMailExpert) {
                $admin = $this->admin->find($projectMailExpert->admin_id);
                $user = $this->user->find($projectMailExpert->expert_id);
                $projectMailExpert->admin_name = $admin->name;
                $experts[$i]["id"] = $projectMailExpert->expert_id;
                $experts[$i]["link"] = $user->textFrontLink();
                $adminName = $admin->name;
                $dt = Carbon::parse($projectMailExpert->date_send);
                $dateSend = $dt->year."-".$dt->month."-".$dt->day;
                $i++;
            }
            if (isset($dateSend) && isset($adminName)) {
                $questionnaire->mail_send_time    = $dateSend;
                $questionnaire->mail_send_admin   = $adminName;
                $questionnaire->mail_send_experts = $experts;
            }
        }
        return $allQuestionnaires;
    }

    public function allSchedules()
    {
        // $schedules = $this->schedule->with("projectMailExpert")->whereNotNull('schedule')
        //              ->orderBy('project_id', 'desc')
        //              ->get();
        // dd($schedules);
        // die();
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
        $version_id = $body['version_id'];

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

    public function getMailExpert($Model)
    {

    }
}
