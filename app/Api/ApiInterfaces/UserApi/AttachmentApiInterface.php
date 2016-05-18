<?php

namespace Backend\Api\ApiInterfaces\UserApi;

use Backend\Model\Eloquent\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface AttachmentApiInterface
{
    /**
     * @param User $user
     * @return mixed
     */
    public function getAttachment(User $user);

    /**
     * @param User $user
     * @param array $attachments
     * @return mixed
     */
    public function updateAttachment(User $user, array $attachments);

    /**
     * @param User $user
     * @param UploadedFile $file
     * @return mixed
     */
    public function putAttachment(User $user, UploadedFile $file);
}
