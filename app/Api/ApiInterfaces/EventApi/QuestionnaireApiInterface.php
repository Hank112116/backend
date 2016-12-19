<?php

namespace Backend\Api\ApiInterfaces\EventApi;

use Backend\Model\Eloquent\User;

interface QuestionnaireApiInterface
{
    /**
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendNotificationMail(User $user);
}
