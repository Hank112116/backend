<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Group extends Eloquent
{
    protected $table = 'groups';
    protected $primaryKey = 'group_id';
    public static $unguarded = true;
    public $timestamps = false;
}
