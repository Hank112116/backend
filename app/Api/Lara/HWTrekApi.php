<?php

namespace Backend\Api\Lara;

use Backend\Enums\API\Response\Key\OAuthKey;
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
        $authorization = session(OAuthKey::TOKEN_TYPE) . ' ' . session(OAuthKey::ACCESS_TOKEN);

        $this->curl->setHeader('Authorization', $authorization);
    }
}
