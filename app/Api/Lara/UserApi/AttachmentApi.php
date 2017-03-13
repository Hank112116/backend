<?php

namespace Backend\Api\Lara\UserApi;

use Backend\Api\ApiInterfaces\UserApi\AttachmentApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\URI\API\HWTrek\UserApiEnum;
use Backend\Model\Eloquent\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachmentApi extends BasicApi implements AttachmentApiInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAttachment(User $user)
    {
        $uri  = $this->hwtrek_url . str_replace('(:num)', $user->id(), UserApiEnum::ATTACHMENT);

        return $this->get($uri);
    }

    /**
     * {@inheritDoc}
     */
    public function updateAttachment(User $user, array $attachments)
    {
        $uri = $this->hwtrek_url . str_replace('(:num)', $user->id(), UserApiEnum::ATTACHMENT);

        $options = [
            'json' => [
                'attachments' => $attachments
            ]
        ];
        return $this->patch($uri, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function putAttachment(User $user, UploadedFile $file)
    {
        $uri  = $this->hwtrek_url . str_replace('(:num)', $user->id(), UserApiEnum::ATTACHMENT);

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

        $response = $this->post($uri, $options);

        unlink($file_path);

        return $response;
    }
}
