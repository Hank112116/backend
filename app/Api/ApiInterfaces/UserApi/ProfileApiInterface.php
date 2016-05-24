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
}
