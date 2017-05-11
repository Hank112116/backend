<?php

namespace Backend\Api\Lara\UserApi;

use Backend\Api\ApiInterfaces\UserApi\ProfileApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\URI\API\HWTrek\UserApiEnum;
use Backend\Model\Eloquent\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfileApi extends BasicApi implements ProfileApiInterface
{
    /**
     * {@inheritDoc}
     */
    public function disable(User $user)
    {
        $uri = str_replace('(:num)', $user->user_id, UserApiEnum::PRIVILEGE);
        $url = $this->hwtrek_url . $uri;

        return $this->delete($url);
    }

    /**
     * {@inheritDoc}
     */
    public function enable(User $user)
    {
        $uri = str_replace('(:num)', $user->user_id, UserApiEnum::PRIVILEGE);
        $url = $this->hwtrek_url . $uri;

        return $this->post($url);
    }

    /**
     * {@inheritDoc}
     */
    public function uploadCompanyLogo(User $user, UploadedFile $file)
    {
        $uri  = $this->hwtrek_url . str_replace('(:num)', $user->id(), UserApiEnum::UPLOAD_COMPANY_LOGO);

        $upload_dir = '/tmp/';

        $file->move($upload_dir, $file->getClientOriginalName());
        $file_path = $upload_dir . $file->getClientOriginalName();

        $options = [
            'multipart' => [
                [
                    'name'     => 'picture',
                    'contents' => fopen($file_path, "r"),
                ]
            ]
        ];

        $response = $this->post($uri, $options);

        unlink($file_path);

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function transformAccountType(User $user, string $user_type)
    {
        $uri = str_replace('(:num)', $user->user_id, UserApiEnum::TRANSFORM_ACCOUNT_TYPE);
        $url = $this->hwtrek_url . $uri;

        $options = [
            'json' => [
                'userType' => $user_type
            ]
        ];

        return $this->put($url, $options);
    }
}
