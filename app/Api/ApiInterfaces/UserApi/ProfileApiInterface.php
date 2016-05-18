<?php

namespace Backend\Api\ApiInterfaces\UserApi;

use Backend\Model\Eloquent\User;

interface ProfileApiInterface
{
    /**
     * @param User $user
     * @return mixed
     */
    public function disable(User $user);

    /**
     * @param User $user
     * @return mixed
     */
    public function enable(User $user);
}
