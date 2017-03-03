<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class LowPriorityProject extends Eloquent
{
    protected $table = 'low_priority_project';
    protected $primaryKey = 'project_id';
    public $timestamps = false;
    public static $unguarded = true;

    public function getKeyName()
    {
        return $this->primaryKey;
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }
}
