<?php

namespace Backend\Api\Lara\AuthApi;

use Backend\Api\ApiInterfaces\AuthApi\OAuthApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\GrantTypeRegistry;
use Backend\Enums\URI\API\HWTrekApiEnum;
use Backend\Facades\Log;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\ConnectException;

class OAuthApi extends BasicApi implements OAuthApiInterface
{
    /**
     * {@inheritDoc}
     */
    public function password($username, $password)
    {
        $csrf_token = $this->getCSRFToken();

        if (is_null($csrf_token)) {

        }

        $url = $this->hwtrek_url . HWTrekApiEnum::OAUTH_TOKEN;

        $options = [
            'headers' => [
                'X-Csrf-Token'  => $csrf_token
            ],
            'json'    => [
                'grant_type'    => GrantTypeRegistry::PASSWORD,
                'username'      => $username,
                'password'      => $password
            ],
            'auth'    => [
                config('api.hwtrek_client_id'),
                config('api.hwtrek_client_secret'),
            ],
        ];

        return $this->post($url, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function clientCredentials()
    {
        $url             = $this->hwtrek_url . HWTrekApiEnum::OAUTH_TOKEN;

        $options = [
            'json'    => [
                'grant_type'    => GrantTypeRegistry::CLIENT_CREDENTIALS,
            ],
            'auth'    => [
                config('api.hwtrek_client_id'),
                config('api.hwtrek_client_secret'),
            ],
        ];

        return $this->post($url, $options);
    }

    /**
     * Access HWTrek home page, get CSRF token
     *
     * @return string
     */
    private function getCSRFToken()
    {
        try {
            $url = $this->hwtrek_url;

            $response = $this->client->get($url);

            $cookies = $response->getHeader('Set-Cookie');

            foreach ($cookies as $cookie) {
                $set_cookie = SetCookie::fromString($cookie);
                if ($set_cookie->getName() === 'csrf') {
                    return $set_cookie->getValue();
                }
            }

            return null;
        } catch (ConnectException $e) {
            Log::error($e->getMessage());

            return null;
        }
    }
}
