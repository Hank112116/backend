<?php

namespace Backend\Api\Lara;

use Backend\Enums\GrantTypeRegistry;
use Backend\Enums\URI\API\HWTrekApiEnum;
use Guzzle\Http\Exception\CurlException;
use Curl\Curl;

/**
 * HWTrek API
 * @author Hank
 **/
abstract class HWTrekApi extends BasicApi
{
    public function __construct()
    {
        parent::__construct();
        $this->setAccessToken();
    }
    
    public function __destruct()
    {
        $this->curl->close();
    }

    /**
     * @return Curl
     */
    private function setAccessToken()
    {
        $curl            = $this->curl;
        $client_id       = config('api.hwtrek_client_id');
        $client_secret   = config('api.hwtrek_client_secret');
        $url             = $this->hwtrek_url . HWTrekApiEnum::OAUTH_TOKEN;
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, config('api.curl_ssl_verifypeer'));
        $curl->setBasicAuthentication($client_id, $client_secret);
        $curl->post($url, ['grant_type' => GrantTypeRegistry::CLIENT_CREDENTIALS]);
        if ($curl->error) {
            throw new CurlException($curl->errorMessage);
        }
        $curl->setHeader('Authorization', "{$curl->response->token_type} {$curl->response->access_token}");
        $curl->setHeader('Content-Type', 'application/json');
        return $curl;
    }
}
