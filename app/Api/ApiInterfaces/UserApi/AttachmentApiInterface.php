<?php

namespace Backend\Api\ApiInterfaces\UserApi;

use Backend\Model\Eloquent\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

interface AttachmentApiInterface
{
    /**
     * @var User $user
     * @return Response
     */
    public function getAttachment(User $user);

    /**
     * @var User $user
     * @param array $attachments
     * @return Response
     */
    public function updateAttachment(User $user, array $attachments);

    /**
     * @var User $user
     * @param UploadedFile $file
     * @return Response
     */
    public function putAttachment(User $user, UploadedFile $file);
}
