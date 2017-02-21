<?php
namespace Backend\Model\Report\Entity;

use Backend\Model\Eloquent\User;

class MemberMatch
{
    protected $user;
    private $pm_recommend;
    private $pm_referrals;
    private $user_recommend;
    private $user_referrals;

    /**
     * MemberMatch constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user           = $user;
        $this->pm_recommend   = 0;
        $this->user_recommend = 0;
        $this->pm_referrals   = 0;
        $this->user_referrals = 0;
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->user->id();
    }

    /**
     * @return string
     */
    public function role()
    {
        return $this->user->textType();
    }

    /**
     * @return string
     */
    public function fullName()
    {
        return $this->user->textFullName();
    }

    /**
     * @return string
     */
    public function link()
    {
        return $this->user->textFrontLink();
    }

    /**
     * @return string
     */
    public function company()
    {
        return $this->user->company;
    }

    /**
     * @return null|string
     */
    public function companyLink()
    {
        return $this->user->getCompanyLink();
    }

    /**
     * @return bool
     */
    public function isSuspended()
    {
        return $this->user->isSuspended();
    }

    /**
     * @return string
     */
    public function textStatus()
    {
        return $this->user->textStatus();
    }

    /**
     * @return string
     */
    public function textActive()
    {
        return $this->user->textActive();
    }

    /**
     * @return string
     */
    public function textEmailVerify()
    {
        return $this->user->textEmailVerify();
    }

    /**
     * @return null|string
     */
    public function internalDescription()
    {
        if ($this->user->internalUserMemo) {
            return $this->user->internalUserMemo->description;
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function internalAction()
    {
        if ($this->user->internalUserMemo) {
            return $this->user->internalUserMemo->report_action;
        }

        return null;
    }

    /**
     * @return int
     */
    public function getCountPMRecommend()
    {
        return $this->pm_recommend;
    }

    /**
     * @return int
     */
    public function getCountUserRecommend()
    {
        return $this->user_recommend;
    }

    /**
     * @return int
     */
    public function getCountPMReferrals()
    {
        return $this->pm_referrals;
    }

    /**
     * @return int
     */
    public function getCountUserReferrals()
    {
        return $this->user_referrals;
    }

    public function countPMRecommend()
    {
        $this->pm_recommend++;
    }

    public function countUserRecommend()
    {
        $this->user_recommend++;
    }

    public function countPMReferrals()
    {
        $this->pm_referrals++;
    }

    public function countUserReferrals()
    {
        $this->user_referrals++;
    }

    /**
     * @return bool
     */
    public function isCreator()
    {
        return $this->user->isCreator();
    }

    /**
     * @return bool
     */
    public function isExpert()
    {
        return $this->user->isExpert();
    }

    /**
     * @return bool
     */
    public function isPremiumCreator()
    {
        return $this->user->isPremiumCreator();
    }

    /**
     * @return bool
     */
    public function isPremiumExpert()
    {
        return $this->user->isPremiumExpert();
    }

    /**
     * @return bool
     */
    public function isPendingExpert()
    {
        return $this->user->isPendingExpert();
    }

    /**
     * @return bool
     */
    public function isHWTrekPM()
    {
        return $this->user->isHWTrekPM();
    }
}
