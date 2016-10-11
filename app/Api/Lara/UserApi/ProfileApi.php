<?php

namespace Backend\Api\Lara\UserApi;

use Backend\Api\ApiInterfaces\UserApi\ProfileApiInterface;
use Backend\Api\Lara\HWTrekApi;
use Backend\Enums\URI\API\HWTrek\UserApiEnum;
use Backend\Enums\URI\API\HWTrekApiEnum;
use Backend\Model\Eloquent\User;

class ProfileApi extends HWTrekApi implements ProfileApiInterface
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
        $r   = $this->delete($url);
        return $this->response((array) $r);
    }

    /**
     * {@inheritDoc}
     */
    public function enable()
    {
        $uri = str_replace('(:num)', $this->user->user_id, UserApiEnum::PRIVILEGE);
        $url = $this->hwtrek_url . $uri;
        $r   = $this->post($url);
        return $this->response((array) $r);
    }

    /**
     * {@inheritDoc}
     */
    public function approveExpert($user_type)
    {
        $data = ['userType' => $user_type];
        $uri = str_replace('(:num)', $this->user->user_id, UserApiEnum::APPROVE_EXPERT);
        $url = $this->hwtrek_url . $uri;
        $r    = $this->patch($url, $data);
        return $this->response((array) $r);
    }
}
