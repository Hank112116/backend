<?php

namespace Backend\Api\ApiInterfaces\AuthApi;

interface OAuthApiInterface
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function password($username, $password);

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function clientCredentials();
}
