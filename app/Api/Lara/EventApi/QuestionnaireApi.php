<?php
namespace Backend\Api\Lara\EventApi;

use Backend\Api\ApiInterfaces\EventApi\QuestionnaireApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\EventEnum;
use Backend\Enums\URI\API\HWTrekApiEnum;
use Backend\Model\Eloquent\User;

class QuestionnaireApi extends BasicApi implements QuestionnaireApiInterface
{
    public function sendNotificationMail(User $user)
    {
        $url = $this->hwtrek_url . HWTrekApiEnum::EVENT . HWTrekApiEnum::API . HWTrekApiEnum::USER . '/' . $user->user_id . HWTrekApiEnum::EVENT_QUESTIONNAIRE . '/' . EventEnum::AIT_2017_Q2_SUBJECT;

        $this->post($url . '/' . 'notification-mail');
    }
}
