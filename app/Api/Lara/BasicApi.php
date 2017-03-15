<?php

namespace Backend\Api\Lara;

use Backend\Enums\API\ApiStatusEnum;
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

    public function __construct(Client $client)
    {
        $this->hwtrek_url = 'https://' . config('app.front_domain');
        $this->client     = $client;
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

            $this->validateResponse($response);

            $this->recordActionLog($url, $options, $response);

            return $this->response($response);
        } catch (ConnectException $e) {
            Log::error($e->getMessage(), $e->getTrace());

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

            $this->validateResponse($response);

            $this->recordActionLog($url, $options, $response);

            return $this->response($response);
        } catch (ConnectException $e) {
            Log::error($e->getMessage(), $e->getTrace());

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

            $this->validateResponse($response);

            $this->recordActionLog($url, $options, $response);

            return $this->response($response);
        } catch (ConnectException $e) {
            Log::error($e->getMessage(), $e->getTrace());

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

            $this->validateResponse($response);

            $this->recordActionLog($url, $options, $response);

            return $this->response($response);
        } catch (ConnectException $e) {
            Log::error($e->getMessage(), $e->getTrace());

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

            $this->validateResponse($response);

            $this->recordActionLog($url, $options, $response);

            return $this->response($response);
        } catch (ConnectException $e) {
            Log::error($e->getMessage(), $e->getTrace());

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
        session()->flash(OAuthKey::API_SERVER_STATUS, ApiStatusEnum::STOP_STATUS);

        return Response::create([], Response::HTTP_SERVICE_UNAVAILABLE, ['Retry-After' => 120]);
    }

    /**
     * @param string $url
     * @param array $options
     * @param ResponseInterface $response
     */
    private function recordActionLog(string $url, array $options, ResponseInterface $response)
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

    /**
     * Validate response's http status code
     *
     * @param ResponseInterface $response
     */
    private function validateResponse(ResponseInterface $response)
    {
        if ($response->getStatusCode() === Response::HTTP_UNAUTHORIZED) {
            session()->flash(OAuthKey::API_SERVER_STATUS, ApiStatusEnum::UNAUTHORIZED_STATUS);
        }
    }
}
