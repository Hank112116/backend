<?php

namespace Backend\Api\ApiInterfaces\UserApi;

use Backend\Model\Eloquent\User;

interface ProfileApiInterface
{
    /**
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function disable(User $user);

    /**
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function enable(User $user);

    /**
     * @param User $user
     * @param $user_type
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function approveExpert(User $user, $user_type);
}
