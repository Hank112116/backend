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
        $csrf = $this->getCSRFToken();

        $url = $this->hwtrek_url . HWTrekApiEnum::OAUTH_TOKEN;
        $this->curl->setBasicAuthentication($username, $password);
        $this->curl->setHeader('X-Csrf-Token', $csrf);
        $this->post($url, ['grant_type' => GrantTypeRegistry::PASSWORD]);

        if ($this->curl->error) {
            return $this->response();
        }

        return $this->response((array) $this->curl->response);
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
        $this->post($url, ['grant_type' => GrantTypeRegistry::CLIENT_CREDENTIALS]);

        if ($this->curl->error) {
            return $this->response();
        }

        return $this->response((array) $this->curl->response);
    }

    /**
     * {@inheritdoc}
     */
    public function profile()
    {
        $authorization = session('token_type') . ' ' . session('access_token');

        $this->curl->setHeader('Authorization', $authorization);

        $this->curl->get('https://dev.hwtrek.com/apis/users/1145/profile/setting');

        if ($this->curl->error) {
            return $this->response();
        }

        return $this->response((array) $this->curl->response);
    }
}
