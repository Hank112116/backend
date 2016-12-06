<?php

namespace Backend\Api\Lara;

use Backend\Enums\API\Response\Key\OAuthKey;
use Backend\Facades\Log;
use Illuminate\Http\Response;
use Curl\Curl;

/**
 * HWTrek API
 * @author Hank
 **/
abstract class BasicApi
{
    protected $curl;
    protected $hwtrek_url;

    public function __construct()
    {
        $this->hwtrek_url = 'https://' . config('app.front_domain');
        //$this->hwtrek_url = 'http://10.10.55.66';
        $this->curl       = $this->initCurl();
    }

    /**
     * @param $url
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function get($url, $data = [])
    {
        $this->curl->get($url, $data);

        $data['method'] = 'GET';
        $data['url']    = $url;
        $this->recordActionLog($data);

        if ($this->curl->error) {
            return $this->response();
        }

        return $this->response((array) $this->curl->response);
    }

    /**
     * @param $url
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function post($url, $data = [])
    {
        $this->curl->post($url, $data);

        $data['method'] = 'POST';
        $data['url']    = $url;
        $this->recordActionLog($data);

        if ($this->curl->error) {
            return $this->response();
        }

        return $this->response((array) $this->curl->response);
    }

    /**
     * @param $url
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function patch($url, $data = [])
    {
        $this->curl->patch($url, json_encode($data));

        $data['method'] = 'PATCH';
        $data['url']    = $url;
        $this->recordActionLog($data);

        if ($this->curl->error) {
            return $this->response();
        }

        return $this->response((array) $this->curl->response);
    }

    /**
     * @param $url
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function put($url, $data = [])
    {
        $this->curl->put($url, $data);

        $data['method'] = 'PUT';
        $data['url']    = $url;
        $this->recordActionLog($data);

        if ($this->curl->error) {
            return $this->response();
        }

        return $this->response((array) $this->curl->response);
    }

    /**
     * @param $url
     * @param array $query_parameters
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function delete($url, $query_parameters = [], $data = [])
    {
        $this->curl->delete($url, $query_parameters, $data);

        $data['method'] = 'DELETE';
        $data['url']    = $url;
        $this->recordActionLog($data);

        if ($this->curl->error) {
            return $this->response();
        }

        return $this->response((array) $this->curl->response);
    }

    /**
     * @return int
     */
    protected function getHttpStatusCode()
    {
        return $this->curl->httpStatusCode;
    }

    /**
     * @param int $http_code
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function response($data = [])
    {
        if ($this->getHttpStatusCode() === 0) {
            session()->put(OAuthKey::API_SERVER_STATUS, 'stop');

            return Response::create($data, Response::HTTP_GATEWAY_TIMEOUT);
        }

        return Response::create($data, $this->getHttpStatusCode());
    }

    /**
     * @return Curl
     */
    private function initCurl()
    {
        $cookie_file = config('app.tmp_folder') . session()->getId();

        $curl = new Curl();

        // Saving Cookie with CURL
        $curl->setHeader('Connection', 'keep-alive');
        $curl->setCookieJar($cookie_file);
        $curl->setCookieFile($cookie_file);
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curl->setOpt(CURLOPT_COOKIESESSION, true);
        $curl->setOpt(CURLOPT_TIMEOUT, 30);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, config('api.curl_ssl_verifypeer'));
        $curl->setReferer($this->hwtrek_url);
        $curl->setHeader('Content-Type', 'application/json');
        $curl->setHeader('X-Requested-With', 'XMLHttpRequest');

        return $curl;
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
