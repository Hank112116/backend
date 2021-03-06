<?php

namespace Backend\Api\ApiInterfaces\UserApi;

use Backend\Model\Eloquent\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @var User $user
     * @param UploadedFile $file
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function uploadCompanyLogo(User $user, UploadedFile $file);

    /**
     * @param User    $user
     * @param string  $user_type
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function transformAccountType(User $user, string $user_type);
}
