<?php

namespace Backend\Api\Lara;

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
        $this->curl       = $this->initCurl();
        $this->hwtrek_url = 'https://' . config('app.front_domain');
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
     * @return Curl
     */
    private function initCurl()
    {
        $cookie_file = storage_path('app/tmp/' . session()->getId());

        $curl = new Curl();

        // Saving Cookie with CURL
        $curl->setHeader('Connection', 'keep-alive');
        $curl->setCookieJar($cookie_file);
        $curl->setCookieFile($cookie_file);
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curl->setOpt(CURLOPT_COOKIESESSION, true);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, config('api.curl_ssl_verifypeer'));

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
