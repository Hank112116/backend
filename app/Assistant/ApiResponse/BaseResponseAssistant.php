<?php

namespace Backend\Assistant\ApiResponse;

use Symfony\Component\HttpFoundation\Response;

abstract class BaseResponseAssistant
{
    /* @var Response $response*/
    protected $response;

    /**
     * @return array
     */
    public function decode()
    {
        return json_decode($this->response->getContent(), true);
    }
}
