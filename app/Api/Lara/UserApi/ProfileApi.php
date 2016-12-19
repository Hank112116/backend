<?php

namespace Backend\Api\Lara\UserApi;

use Backend\Api\ApiInterfaces\UserApi\ProfileApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\URI\API\HWTrek\UserApiEnum;
use Backend\Model\Eloquent\User;

class ProfileApi extends BasicApi implements ProfileApiInterface
{
    /**
     * {@inheritDoc}
     */
    public function disable(User $user)
    {
        $uri = str_replace('(:num)', $user->user_id, UserApiEnum::PRIVILEGE);
        $url = $this->hwtrek_url . $uri;

        return $this->delete($url);
    }

    /**
     * {@inheritDoc}
     */
    public function enable(User $user)
    {
        $uri = str_replace('(:num)', $user->user_id, UserApiEnum::PRIVILEGE);
        $url = $this->hwtrek_url . $uri;

        return $this->post($url);
    }

    /**
     * {@inheritDoc}
     */
    public function approveExpert(User $user, $user_type)
    {
        $uri = str_replace('(:num)', $user->user_id, UserApiEnum::APPROVE_EXPERT);
        $url = $this->hwtrek_url . $uri;

        $options = [
            'json' => [
                'userType' => $user_type
            ]
        ];

        return $this->patch($url, $options);
    }
}
