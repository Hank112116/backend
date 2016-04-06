<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ProjectStatistic extends Eloquent
{
    protected $table = 'project_statistic';
    protected $primaryKey = 'id';
    public static $unguarded = true;
    public $timestamps = false;
}
