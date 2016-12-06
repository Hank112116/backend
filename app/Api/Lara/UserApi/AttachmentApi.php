<?php
namespace Backend\Api\Lara\UserApi;

use Backend\Api\ApiInterfaces\UserApi\AttachmentApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\URI\API\HWTrek\UserApiEnum;
use Backend\Api\Lara\HWTrekApi;
use Backend\Model\Eloquent\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachmentApi extends BasicApi implements AttachmentApiInterface
{
    private $url;
    private $user;

    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
        $uri        = str_replace('(:num)', $user->user_id, UserApiEnum::ATTACHMENT);
        $this->url  = $this->hwtrek_url . $uri;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttachment()
    {
        return $this->get($this->url);
    }

    /**
     * {@inheritDoc}
     */
    public function updateAttachment(array $attachments)
    {
        $options = [
            'json' => [
                'attachments' => $attachments
            ]
        ];
        return $this->patch($this->url, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function putAttachment(UploadedFile $file)
    {
        $upload_dir = '/tmp/';

        $file->move($upload_dir, $file->getClientOriginalName());
        $file_path = $upload_dir . $file->getClientOriginalName();

        $options = [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => fopen($file_path, "r"),
                ]
            ]
        ];

        $response = $this->post($this->url, $options);

        unlink($file_path);

        return $response;
    }
}
