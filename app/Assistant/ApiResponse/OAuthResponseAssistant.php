<?php
namespace Backend\Assistant\ApiResponse;

use Backend\Enums\API\Response\Key\OAuthKey;
use Symfony\Component\HttpFoundation\Response;

class OAuthResponseAssistant extends BaseResponseAssistant
{
    /**
     * OAuthResponseAssistant constructor.
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @param Response $response
     * @return OAuthResponseAssistant
     */
    public static function create(Response $response)
    {
        return new OAuthResponseAssistant($response);
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        $response = $this->decode();

        return $response[OAuthKey::ACCESS_TOKEN];
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        $response = $this->decode();

        return $response[OAuthKey::TOKEN_TYPE];
    }

    /**
     * @return array
     */
    public function normalize()
    {
        return [
            OAuthKey::ACCESS_TOKEN  => $this->getAccessToken(),
            OAuthKey::TOKEN_TYPE    => $this->getTokenType()
        ];
    }
}
