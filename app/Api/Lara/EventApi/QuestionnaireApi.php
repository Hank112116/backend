<?php
namespace Backend\Api\Lara\EventApi;

use Backend\Api\ApiInterfaces\EventApi\QuestionnaireApiInterface;
use Backend\Enums\EventEnum;
use Backend\Enums\URI\API\HWTrekApiEnum;
use Backend\Api\Lara\HWTrekApi;
use Backend\Model\Eloquent\User;

class QuestionnaireApi extends HWTrekApi implements QuestionnaireApiInterface
{
    private $url;
    private $user;

    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
        $this->url  = 'https://' . $this->front_domain . HWTrekApiEnum::EVENT . HWTrekApiEnum::API . HWTrekApiEnum::USER . '/' . $user->user_id . HWTrekApiEnum::EVENT_QUESTIONNAIRE . '/' . EventEnum::AIT_2016_Q4_SUBJECT;
    }

    public function sendNotificationMail()
    {
        $this->post($this->url . '/' . 'notification-mail');
    }
}
