<?php
namespace Backend\Assistant\ApiResponse\UserApi;

use Backend\Assistant\ApiResponse\BaseResponseAssistant;
use Backend\Model\Attachment;
use Illuminate\Support\Collection;
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
        $attachments = $this->decode();

        if (is_null($attachments)) {
            return false;
        }

        return true;
    }

    /**
     * @return Collection
     */
    public function getAttachments()
    {
        $collection = Collection::make();

        if (!$this->haveAttachments()) {
            return $collection;
        }

        foreach ($this->decode() as $item) {
            $collection->push(new Attachment($item));
        }

        return $collection;
    }

    /**
     * @return Attachment
     */
    public function getAttachment()
    {
        return new Attachment($this->decode());
    }
}
