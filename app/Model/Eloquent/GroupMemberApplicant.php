<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class GroupMemberApplicant extends Eloquent
{
    const REFERRAL_PM   = 'referral-pm';
    const REFERRAL_USER = 'referral-user';
    const APPLY_PM      = 'apply-pm';
    const APPLY_USER    = 'apply-user';

    protected $table = 'group_member_applicants';
    protected $primaryKey = 'applicant_id';
    public static $unguarded = true;
    public $timestamps = false;

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function referralUser()
    {
        return $this->hasOne(User::class, 'user_id', 'referral');
    }
}
