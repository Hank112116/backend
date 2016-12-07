<?php

namespace Backend\Api\ApiInterfaces\UserApi;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

interface AttachmentApiInterface
{
    /**
     * @return Response
     */
    public function getAttachment();

    /**
     * @param array $attachments
     * @return Response
     */
    public function updateAttachment(array $attachments);

    /**
     * @param UploadedFile $file
     * @return Response
     */
    public function putAttachment(UploadedFile $file);
}
