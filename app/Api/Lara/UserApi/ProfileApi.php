<?php

namespace Backend\Api\Lara\UserApi;

use Backend\Api\ApiInterfaces\UserApi\ProfileApiInterface;
use Backend\Api\Lara\HWTrekApi;
use Backend\Enums\URI\API\HWTrekApiEnum;
use Backend\Model\Eloquent\User;

class ProfileApi extends HWTrekApi implements ProfileApiInterface
{
    private $user;
    private $url;

    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
        $this->url  = 'https://' . $this->front_domain . HWTrekApiEnum::API . HWTrekApiEnum::USER . '/' . $user->user_id . HWTrekApiEnum::PRIVILEGE;
    }

    /**
     * {@inheritDoc}
     */
    public function disable()
    {
        $r = $this->delete($this->url);
        return $this->response((array) $r);
    }

    /**
     * {@inheritDoc}
     */
    public function enable()
    {
        $r = $this->post($this->url);
        return $this->response((array) $r);
    }
}
