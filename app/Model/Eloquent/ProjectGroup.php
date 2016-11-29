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
        return $this->hasMany(GroupMemberApplicant::class, 'group_id', 'group_id')->orderBy('apply_date', 'desc');
    }

    public function group()
    {
        return $this->hasMany(Group::class, 'group_id', 'group_id');
    }

    public function isDefaultGroup($owner_id)
    {
        if ($this->group) {
            foreach ($this->group as $group) {
                if ($group->group_owner == $owner_id) {
                    return true;
                }
            }
        }
        return false;
    }
}
