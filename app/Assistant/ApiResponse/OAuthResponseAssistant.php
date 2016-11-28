<?php
namespace Backend\Assistant\ApiResponse;

use Backend\Enums\API\Response\Key\OAuthKey;
use Symfony\Component\HttpFoundation\Response;

class OAuthResponseAssistant extends BaseResponseAssistant
{
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public static function create(Response $response)
    {
        return new OAuthResponseAssistant($response);
    }

    public function getAccessToken()
    {
        $response = $this->decode();

        return $response[OAuthKey::ACCESS_TOKEN];
    }

    public function getTokenType()
    {
        $response = $this->decode();

        return $response[OAuthKey::TOKEN_TYPE];
    }

    public function normalize()
    {
        return [
            OAuthKey::ACCESS_TOKEN  => $this->getAccessToken(),
            OAuthKey::TOKEN_TYPE    => $this->getTokenType()
        ];
    }
}