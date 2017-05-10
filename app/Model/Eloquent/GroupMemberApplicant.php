<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class GroupMemberApplicant extends Eloquent
{
    const REFERRAL_PM   = 'referral-pm';
    const REFERRAL_USER = 'referral-user';
    const APPLY_PM      = 'apply-pm';
    const APPLY_USER    = 'apply-user';
    const INVITE_OWNER  = 'invite-owner';

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

    public function isPMReferral()
    {
        return $this->event === self::REFERRAL_PM or $this->event === self::APPLY_PM;
    }

    public function isUserReferral()
    {
        return $this->event === self::REFERRAL_USER or $this->event === self::APPLY_USER or $this->event === self::INVITE_OWNER;
    }

    public function isSelfReferral()
    {
        return ($this->event === self::APPLY_PM or $this->event === self::APPLY_USER) and is_null($this->referral);
    }

    public function isMemberReferral()
    {
        return ($this->event === self::REFERRAL_PM or $this->event === self::REFERRAL_USER or $this->event === self::INVITE_OWNER) and !is_null($this->referral);
    }

    public function isRecommendExpert()
    {
        if (is_null($this->user)) {
            return false;
        }

        if ($this->user->isExpert()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getAppliedProjectId()
    {
        $project_id = null;
        $additional_privileges = json_decode($this->additional_privileges, true);
        foreach ($additional_privileges as $privilege) {
            if (in_array('project', $privilege)) {
                $project_id = $privilege[2];   // array index 2 is id
                break;
            }
        }
        return $project_id;
    }

    /**
     * @return User|null
     */
    public function projectOwner()
    {
        return $this->project()->user;
    }

    /**
     * @return Project|null
     */
    public function project()
    {
        $project_id = $this->getAppliedProjectId();

        if (is_null($project_id)) {
            return null;
        }

        $project = new Project();

        $project = $project->find($project_id);

        return $project;
    }

    public function applyDate()
    {
        return $this->apply_date;
    }
}
