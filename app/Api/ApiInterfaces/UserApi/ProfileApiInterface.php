<?php

namespace Backend\Api\ApiInterfaces\UserApi;

interface ProfileApiInterface
{
    /**
     * @return mixed
     */
    public function disable();

    /**
     * @return mixed
     */
    public function enable();

    /**
     * @param $user_type
     * @return mixed
     */
    public function approveExpert($user_type);
}
