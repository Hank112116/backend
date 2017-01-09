<?php

namespace Backend\Assistant\ApiResponse;

use Symfony\Component\HttpFoundation\Response;

abstract class BaseResponseAssistant implements \JsonSerializable
{
    /* @var Response $response*/
    protected $response;

    public function deserialize()
    {
        return json_decode($this->response->getContent());
    }

    /**
     * @return array
     */
    public function decode()
    {
        return json_decode($this->response->getContent(), true);
    }

    public function jsonSerialize()
    {
        return $this->normalize();
    }

    abstract public function normalize();
}
