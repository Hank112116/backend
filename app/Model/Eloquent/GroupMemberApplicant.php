<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class GroupMemberApplicant extends Eloquent
{
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
