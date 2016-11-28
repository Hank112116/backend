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
    public function password($username, $password)
    {
        $url             = $this->hwtrek_url . HWTrekApiEnum::OAUTH_TOKEN;
        $this->curl->setOpt(CURLOPT_SSL_VERIFYPEER, config('api.curl_ssl_verifypeer'));
        $this->curl->setBasicAuthentication($username, $password);
        $this->curl->post($url, ['grant_type' => GrantTypeRegistry::PASSWORD]);

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
        $this->curl->setOpt(CURLOPT_SSL_VERIFYPEER, config('api.curl_ssl_verifypeer'));
        $this->curl->setBasicAuthentication($client_id, $client_secret);
        $this->curl->post($url, ['grant_type' => GrantTypeRegistry::CLIENT_CREDENTIALS]);

        return $this->response((array) $this->curl->response);
    }
}
