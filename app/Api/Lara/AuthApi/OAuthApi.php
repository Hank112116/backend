<?php

namespace Backend\Api\Lara\AuthApi;

use Backend\Api\ApiInterfaces\AuthApi\OAuthApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\GrantTypeRegistry;
use Backend\Enums\URI\API\HWTrekApiEnum;

class OAuthApi extends BasicApi  implements OAuthApiInterface
{
    /**
     * {@inheritDoc}
     */
    public function getCSRFToken()
    {
        $url = $this->hwtrek_url;
        $this->get($url);

        return $this->curl->getResponseCookie('csrf');
    }

    /**
     * {@inheritDoc}
     */
    public function password($username, $password)
    {
        $csrf          = $this->getCSRFToken();
        $client_id     = config('api.hwtrek_client_id');
        $client_secret = config('api.hwtrek_client_secret');

        $url = $this->hwtrek_url . HWTrekApiEnum::OAUTH_TOKEN;

        $this->curl->setHeader('X-Csrf-Token', $csrf);
        $this->curl->setBasicAuthentication($client_id, $client_secret);

        $post_data = [
            'grant_type'    => GrantTypeRegistry::PASSWORD,
            'username'      => $username,
            'password'      =>$password
        ];

        return $this->post($url, $post_data);
    }

    /**
     * {@inheritDoc}
     */
    public function clientCredentials()
    {
        $client_id       = config('api.hwtrek_client_id');
        $client_secret   = config('api.hwtrek_client_secret');
        $url             = $this->hwtrek_url . HWTrekApiEnum::OAUTH_TOKEN;
        $this->curl->setBasicAuthentication($client_id, $client_secret);

        return $this->post($url, ['grant_type' => GrantTypeRegistry::CLIENT_CREDENTIALS]);
    }
}
