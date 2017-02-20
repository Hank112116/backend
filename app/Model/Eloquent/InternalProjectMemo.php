<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class InternalProjectMemo extends Eloquent
{
    protected $table = 'internal_project_memo';
    protected $primaryKey = 'id';
    public static $unguarded = true;

    public function project()
    {
        return $this->belongsTo(Project::class, 'id', 'project_id');
    }

    public function hasProjectManager()
    {
        $managers = json_decode($this->project_managers, true);
        return count($managers) > 0;
    }

    public function textProjectManagers()
    {
        if ($this->hasProjectManager()) {
            $managers = json_decode($this->project_managers, true);
            return implode(',', $managers);
        }
        return null;
    }
}
