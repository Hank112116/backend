<?php namespace Backend\Api\Lara;

use Backend\Model\Eloquent\User;
use Backend\Api\ApiInterfaces\UserApiInterface;

class UserApi extends BaseApi implements UserApiInterface
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function basicColumns($user_id)
    {
        $user = $this->user->find($user_id);

        if (!$user) {
            return $this->jsonFail();
        }

        return $this->jsonOK($user->toBasicArray());
    }
}
