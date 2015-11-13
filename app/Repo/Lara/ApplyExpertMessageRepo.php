<?php
namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\ApplyExpertMessage;

use Backend\Repo\RepoInterfaces\ApplyExpertMessageInterface;

class ApplyExpertMessageRepo implements ApplyExpertMessageInterface
{
    private $apply_expert_message;

    public function __construct(ApplyExpertMessage $apply_expert_message)
    {
        $this->apply_expert_message = $apply_expert_message;
    }

    public function byUserId($user_id)
    {
        return $this->apply_expert_message
                    ->where('user_id', $user_id)
                    ->orderBy('id', 'desc')
                    ->get();
    }
}
