<?php namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ProjectMailExpert extends Eloquent
{

    //
    protected $table = 'project_expert';
    public $timestamps = false;
    public static $unguarded = true;
}
