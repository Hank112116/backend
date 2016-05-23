<?php

namespace Backend\Api\Lara\UserApi;

use Backend\Api\ApiInterfaces\UserApi\ProfileApiInterface;
use Backend\Api\Lara\HWTrekApi;
use Backend\Enums\URI\API\HWTrekApiEnum;
use Backend\Model\Eloquent\User;

class ProfileApi extends HWTrekApi implements ProfileApiInterface
{
    /**
     * {@inheritDoc}
     */
    public function disable(User $user)
    {
        $url = 'https://' . $this->front_domain . HWTrekApiEnum::API . HWTrekApiEnum::USER . '/' . $user->user_id . HWTrekApiEnum::PRIVILEGE;
        $r   = $this->delete($url);
        return $this->response((array) $r);
    }

    /**
     * {@inheritDoc}
     */
    public function enable(User $user)
    {
        $url = 'https://' . $this->front_domain . HWTrekApiEnum::API . HWTrekApiEnum::USER . '/' . $user->user_id . HWTrekApiEnum::PRIVILEGE;
        $r   = $this->post($url);
        return $this->response((array) $r);
    }
}
