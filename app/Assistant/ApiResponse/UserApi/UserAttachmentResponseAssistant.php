<?php
namespace Backend\Assistant\ApiResponse\UserApi;

use Backend\Assistant\ApiResponse\BaseResponseAssistant;
use Symfony\Component\HttpFoundation\Response;

class UserAttachmentResponseAssistant extends BaseResponseAssistant
{
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public static function create(Response $response)
    {
        return new UserAttachmentResponseAssistant($response);
    }

    public function haveAttachments()
    {
        $attachments = $this->deserialize();

        foreach ($attachments as $attachment) {
            return (!is_null($attachment) and $attachment != 'null');
        }
        return false;
    }
}