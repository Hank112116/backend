<?php

namespace Backend\Api\ApiInterfaces\UserApi;

use Backend\Model\Eloquent\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface AttachmentApiInterface
{
    /**
     * @return mixed
     */
    public function getAttachment();

    /**
     * @param array $attachments
     * @return mixed
     */
    public function updateAttachment(array $attachments);

    /**
     * @param UploadedFile $file
     * @return mixed
     */
    public function putAttachment(UploadedFile $file);
}
