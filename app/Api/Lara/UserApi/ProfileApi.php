<?php

namespace Backend\Api\Lara\UserApi;

use Backend\Api\ApiInterfaces\UserApi\ProfileApiInterface;
use Backend\Api\Lara\HWTrekApi;
use Backend\Model\Eloquent\User;

class ProfileApi extends HWTrekApi implements ProfileApiInterface
{
    /**
     * {@inheritDoc}
     */
    public function disable(User $user)
    {
        // TODO: Implement disable() method.
    }

    /**
     * {@inheritDoc}
     */
    public function enable(User $user)
    {
        // TODO: Implement enable() method.
    }
}
