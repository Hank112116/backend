<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ProjectStatistic extends Eloquent
{
    const DEFAULT_STATISTIC = [
        'page_view'          => 0,
        'staff_referral'     => 0,
        'user_referral'      => 0,
        'staff_proposed'     => 0,
        'user_proposed'      => 0,
        'last_referral_time' => null,
        'last_proposed_time' => null
    ];

    protected $table = 'project_statistic';
    protected $primaryKey = 'id';
    public static $unguarded = true;
    public $timestamps = false;
}
