<?php
namespace Backend\Model\Report\Entity;

use Backend\Model\Eloquent\User;

class MemberMatchPM
{
    private $user;
    private $referral_count;

    public function __construct(User $user)
    {
        $this->user           = $user;
        $this->referral_count = 0;
    }

    public function name()
    {
        return $this->user->textFullName();
    }

    public function countReferral()
    {
        $this->referral_count ++;
    }

    public function getCountReferral()
    {
        return $this->referral_count;
    }
}
