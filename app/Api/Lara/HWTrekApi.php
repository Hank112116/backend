<?php

namespace Backend\Api\Lara;

use Backend\Enums\GrantTypeRegistry;
use Backend\Enums\URI\API\HWTrekApiEnum;
use Illuminate\Http\Response;
use Guzzle\Http\Exception\CurlException;
use Curl\Curl;
use Config;
use Log;

/**
 * HWTrek API
 * @author Hank
 **/
class HWTrekApi
{
    protected $curl;
    protected $front_domain;
    protected $hwtrek_url;

    public function __construct()
    {
        $this->front_domain = Config::get('app.front_domain');
        $curl               = new Curl();
        $curl               = $this->setAccessToken($curl);
        $this->curl         = $curl;
        $this->hwtrek_url   = 'https://' . $this->front_domain;
    }
    
    public function __destruct()
    {
        $this->curl->close();
    }

    /**
     * @param Curl $curl
     * @return Curl
     */
    private function setAccessToken(Curl $curl)
    {
        $client_id       = Config::get('api.hwtrek_client_id');
        $client_secret   = Config::get('api.hwtrek_client_secret');
        $url             = 'https://' .  $this->front_domain . HWTrekApiEnum::OAUTH_TOKEN;
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, Config::get('api.curl_ssl_verifypeer'));
        $curl->setBasicAuthentication($client_id, $client_secret);
        $curl->post($url, ['grant_type' => GrantTypeRegistry::CLIENT_CREDENTIALS]);
        if ($curl->error) {
            throw new CurlException($curl->errorMessage);
        }
        $curl->setHeader('Authorization', "{$curl->response->token_type} {$curl->response->access_token}");
        $curl->setHeader('Content-Type', 'application/json');
        return $curl;
    }

    /**
     * @param $url
     * @param array $data
     * @return string
     */
    protected function get($url, $data = [])
    {
        $r = $this->curl->get($url, $data);
        $data['method'] = 'GET';
        $data['url']    = $url;
        $this->recordActionLog($data);
        return $r;
    }

    /**
     * @param $url
     * @param array $data
     */
    protected function post($url, $data = [])
    {
        $r = $this->curl->post($url, $data);
        $data['method'] = 'POST';
        $data['url']    = $url;
        $this->recordActionLog($data);
        return $r;
    }

    /**
     * @param $url
     * @param array $data
     */
    protected function patch($url, $data = [])
    {
        $r = $this->curl->patch($url, json_encode($data));
        $data['method'] = 'PATCH';
        $data['url']    = $url;
        $this->recordActionLog($data);
        return $r;
    }

    /**
     * @param $url
     * @param array $data
     */
    protected function put($url, $data = [])
    {
        $r = $this->curl->put($url, $data);
        $data['method'] = 'PUT';
        $data['url']    = $url;
        $this->recordActionLog($data);
        return $r;
    }

    /**
     * @param $url
     * @param array $query_parameters
     * @param array $data
     */
    protected function delete($url, $query_parameters = [], $data = [])
    {
        $r =  $this->curl->delete($url, $query_parameters, $data);
        $data['method'] = 'DELETE';
        $data['url']    = $url;
        $this->recordActionLog($data);
        return $r;
    }

    /**
     * @return int
     */
    protected function getHttpStatusCode()
    {
        return $this->curl->httpStatusCode;
    }

    /**
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function response($data = [])
    {
        return Response::create($data, $this->getHttpStatusCode());
    }

    /**
     * Record api action log
     *
     * @param $data
     */
    private function recordActionLog($data)
    {
        if ($this->curl->error) {
            Log::error('Call api fail', $data + [
                'error_code' => $this->curl->errorCode,
                'message'    => $this->curl->errorMessage
            ]);
        } else {
            Log::info('Call api', $data);
        }
    }
}
