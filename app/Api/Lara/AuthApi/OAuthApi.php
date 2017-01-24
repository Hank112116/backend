<?php

namespace Backend\Api\Lara\AuthApi;

use Backend\Api\ApiInterfaces\AuthApi\OAuthApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\API\Response\Key\OAuthKey;
use Backend\Enums\GrantTypeRegistry;
use Backend\Enums\URI\API\HWTrekApiEnum;
use Cache;

class OAuthApi extends BasicApi implements OAuthApiInterface
{
    /**
     * {@inheritDoc}
     */
    public function password($username, $password)
    {
        $url = $this->hwtrek_url . HWTrekApiEnum::OAUTH_TOKEN;

        $options = [
            'json'    => [
                'grant_type'    => GrantTypeRegistry::PASSWORD,
                'username'      => $username,
                'password'      => $password
            ],
            'auth'    => [
                config('api.hwtrek_client_id'),
                config('api.hwtrek_client_secret'),
            ],
            'headers' => [
                'X-Csrf-Token' => Cache::get(OAuthKey::HWTREK_CSRF_TOKEN)
            ]
        ];

        return $this->post($url, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function clientCredentials()
    {
        $url = $this->hwtrek_url . HWTrekApiEnum::OAUTH_TOKEN;

        $options = [
            'json'    => [
                'grant_type' => GrantTypeRegistry::CLIENT_CREDENTIALS,
            ],
            'auth'    => [
                config('api.hwtrek_client_id'),
                config('api.hwtrek_client_secret'),
            ],
        ];

        return $this->post($url, $options);
    }
}
