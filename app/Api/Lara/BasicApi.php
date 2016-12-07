<?php

namespace Backend\Api\Lara;

use Backend\Enums\API\Response\Key\OAuthKey;
use Backend\Facades\Log;
use GuzzleHttp\Exception\ConnectException;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Http\Response;
use GuzzleHttp\Client;

/**
 * HWTrek API
 * @author Hank
 **/
abstract class BasicApi
{
    protected $hwtrek_url;
    protected $client;

    public function __construct()
    {
        $this->hwtrek_url = 'https://' . config('app.front_domain');
        $this->client     = $this->initClient();
    }

    /**
     * @param $url
     * @param array $options
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function get($url, $options = [])
    {
        try {
            $response = $this->client->get($url, $options);

            $this->recordActionLog($url, $options, $response);

            return $this->response($response);
        } catch (ConnectException $e) {
            Log::error($e->getMessage());

            return $this->connectExceptionResponse();
        }
    }

    /**
     * @param $url
     * @param array $options
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function post($url, $options = [])
    {
        try {
            $response = $this->client->post($url, $options);

            $this->recordActionLog($url, $options, $response);

            return $this->response($response);
        } catch (ConnectException $e) {
            Log::error($e->getMessage());

            return $this->connectExceptionResponse();
        }
    }

    /**
     * @param $url
     * @param array $options
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function patch($url, $options = [])
    {
        try {
            $response = $this->client->patch($url, $options);

            $this->recordActionLog($url, $options, $response);

            return $this->response($response);
        } catch (ConnectException $e) {
            Log::error($e->getMessage());

            return $this->connectExceptionResponse();
        }
    }

    /**
     * @param $url
     * @param array $options
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function put($url, $options = [])
    {
        try {
            $response = $this->client->put($url, $options);

            $this->recordActionLog($url, $options, $response);

            return $this->response($response);
        } catch (ConnectException $e) {
            Log::error($e->getMessage());

            return $this->connectExceptionResponse();
        }
    }

    /**
     * @param $url
     * @param array $options
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function delete($url, $options = [])
    {
        try {
            $response = $this->client->delete($url, $options);

            $this->recordActionLog($url, $options, $response);

            return $this->response($response);
        } catch (ConnectException $e) {
            Log::error($e->getMessage());

            return $this->connectExceptionResponse();
        }
    }

    /**
     * @param ResponseInterface $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function response(ResponseInterface $response)
    {
        $data = json_decode($response->getBody()->getContents(), true);

        return Response::create($data, $response->getStatusCode());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function connectExceptionResponse()
    {
        session()->put(OAuthKey::API_SERVER_STATUS, 'stop');

        return Response::create([], Response::HTTP_SERVICE_UNAVAILABLE ,['Retry-After' => '120']);
    }

    /**
     * @return Client
     */
    private function initClient()
    {
        $config = [
            'timeout'   => '25.0',
            'cookies'   => true,
            'verify'    => config('api.curl_ssl_verifypeer'),
            'headers'   => [
                'X-Requested-With' => 'XMLHttpRequest',
                'Connection'       => 'keep-alive',
                'Referer'          => $this->hwtrek_url
            ],
            'http_errors' => false
        ];

        $config  = $this->appendAuthorizationHeader($config);

        $client = new Client($config);

        return $client;
    }

    /**
     * @param array $options
     * @return array
     */
    protected function appendAuthorizationHeader(array $options)
    {
        if (session()->has(OAuthKey::TOKEN_TYPE) and session()->has(OAuthKey::ACCESS_TOKEN)) {
            $authorization = session(OAuthKey::TOKEN_TYPE) . ' ' . session(OAuthKey::ACCESS_TOKEN);
            $options['headers']['Authorization'] = $authorization;
        }

        return $options;
    }

    /**
     * @param string $url
     * @param array $options
     * @param ResponseInterface $response
     */
    private function recordActionLog(string $url,array $options, ResponseInterface $response)
    {
        $data = [
            'url'      => $url,
            'options'  => $options,
            'response' => [
                'http_code' => $response->getStatusCode(),
            ]
        ];

        Log::info('Call api', $data);
    }
}
