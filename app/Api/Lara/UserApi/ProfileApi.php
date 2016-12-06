<?php

namespace Backend\Api\Lara\UserApi;

use Backend\Api\ApiInterfaces\UserApi\ProfileApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\URI\API\HWTrek\UserApiEnum;
use Backend\Model\Eloquent\User;

class ProfileApi extends BasicApi implements ProfileApiInterface
{
    private $user;

    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
    }

    /**
     * {@inheritDoc}
     */
    public function disable()
    {
        $uri = str_replace('(:num)', $this->user->user_id, UserApiEnum::PRIVILEGE);
        $url = $this->hwtrek_url . $uri;

        return $this->delete($url);
    }

    /**
     * {@inheritDoc}
     */
    public function enable()
    {
        $uri = str_replace('(:num)', $this->user->user_id, UserApiEnum::PRIVILEGE);
        $url = $this->hwtrek_url . $uri;

        return $this->post($url);
    }

    /**
     * {@inheritDoc}
     */
    public function approveExpert($user_type)
    {
        $uri = str_replace('(:num)', $this->user->user_id, UserApiEnum::APPROVE_EXPERT);
        $url = $this->hwtrek_url . $uri;

        $options = [
            'json' => [
                'userType' => $user_type
            ]
        ];

        return $this->patch($url, $options);
    }
}
