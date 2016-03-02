<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ProjectTeam extends Eloquent
{
    protected $table = 'project_team';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public static $unguarded = true;

    public function project()
    {
        return $this->hasOne(Project::class, 'project_id', 'id');
    }
}
