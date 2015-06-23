<?php

namespace Backend\Model\Eloquent;

use DB;
use Input;
use Requests;

/**
 * HubSchedule Model
 **/

class HubSchedule extends Project
{

    protected $table = 'project';
    protected $primaryKey = 'project_id';
    public $timestamps = false;
    public static $unguarded = true;
    public $tasks = [];
    public $tasks_sum = 0;
    public static $options = [
        'notelevel'            => [
            0 => 'Not graded', 1 => 'Grade A', 2 => 'Grade B',
            3 => 'Grade C ', 4 => 'Grade D',
        ],
    ];
    public function projectMailExpert()
    {
        return $this->hasMany(ProjectMailExpert::class, 'project_id', 'project_id');
    }
    public function textFrontEditLink()
    {
        return "//" . config('app.front_domain') . "/hub/manage-schedule-panel/{$this->project_id}/admin-edit";
    }
    public function textNoteLevel()
    {
         return static::$options['notelevel'][$this->hub_note_level];
    }
    public function getOriginVersion()
    {
        $row = DB::table('pms_schedule_version')
            ->select('version_id')
            ->where('project_id', '=', $this->project_id)
            ->where('admin_version', '=', 1)
            ->first();

        return $row ? $row->version_id : '';
    }

    public function getApproveVersion()
    {
        $row = DB::table('pms_schedule_version')
            ->select('version_id')
            ->where('project_id', '=', $this->project_id)
            ->where('admin_version', '=', 1)
            ->where('create_time', '=', $this->hub_approve_time)
            ->first();

        return $row ? $row->version_id : '';
    }

    public function getHubManagerNames()
    {
        if (!$this->hub_managers) {
            return [];
        }

        return Adminer::whereIn('id', explode(',', $this->hub_managers))->lists('name');
    }

    public function getDeletedHubManagerNames()
    {
        if (!$this->hub_managers) {
            return [];
        }

        return Adminer::onlyTrashed()->whereIn('id', explode(',', $this->hub_managers))->lists('name');
    }

    public function approve()
    {
        $create_time = $this->recordApproveVersion();

        $this->hub_approve      = 1;
        $this->hub_approve_time = $create_time;
        $this->save();
    }

    public function recordApproveVersion()
    {
        $token        = config('app.hub_token');
        $snapshot_api = 'https://' . config('app.front_domain') . '/hub/apis/admin-snapshot';

        $header  = [];
        $data    = [
            'projectId' => $this->project_id,
            'token'     => md5("{$this->project_id}_{$token}")
        ];
        $options = ['verify' => false, 'verifyname' => false];

        $result = Requests::post($snapshot_api, $header, $data, $options);
        $obj    = json_decode($result->body);

        $version = DB::table('pms_schedule_version')
            ->select('create_time')
            ->where('version_id', $obj->version_id)
            ->first();

        return $version->create_time;
    }

    // update project hub_managers
    public function updateHubManagers()
    {
        $managers           = Input::get('managers', []);
        $this->hub_managers = implode(',', $managers);
        $this->save();
    }

    public function inHubManagers($manager_id)
    {
        return in_array($manager_id, explode(',', $this->hub_managers));
    }

    public function isDeleted()
    {
        return $this->is_deleted;
    }
}
