<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ProjectManager extends Eloquent
{
    protected $table = 'project_manager';
    protected $primaryKey = 'project_id';
    public static $unguarded = true;
    public $timestamps = false;

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function adminer()
    {
        return $this->belongsTo(Adminer::class, 'pm_id', 'hwtrek_member');
    }
}
