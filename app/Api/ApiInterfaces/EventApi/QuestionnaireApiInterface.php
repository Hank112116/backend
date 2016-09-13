<?php

namespace Backend\Api\ApiInterfaces\EventApi;

use Backend\Model\Eloquent\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface QuestionnaireApiInterface
{
    /**
     * @return mixed
     */
    public function sendNotificationMail();
}
