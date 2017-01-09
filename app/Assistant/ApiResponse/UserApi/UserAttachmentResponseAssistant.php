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

    /**
     * @param Response $response
     * @return UserAttachmentResponseAssistant
     */
    public static function create(Response $response)
    {
        return new UserAttachmentResponseAssistant($response);
    }

    /**
     * @return bool
     */
    public function haveAttachments()
    {
        $attachments = $this->deserialize();

        if (is_null($attachments)) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function normalize()
    {
        return $this->decode();
    }
}
