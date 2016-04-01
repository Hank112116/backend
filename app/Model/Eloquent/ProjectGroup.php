<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ProjectGroup extends Eloquent
{
    protected $table = 'project_groups';
    protected $primaryKey = 'group_id';
    public static $unguarded = true;
    public $timestamps = false;

    public function memberApplicant()
    {
        return $this->hasMany(GroupMemberApplicant::class, 'group_id', 'group_id');
    }
}
