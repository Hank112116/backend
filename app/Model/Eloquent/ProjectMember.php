<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ProjectMember extends Eloquent
{
    protected $table = 'project_members';
    protected $primaryKey = 'project_id';
    public static $unguarded = true;
    public $timestamps = false;
}
