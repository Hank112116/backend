<?php

namespace Backend\Api\ApiInterfaces\AuthApi;

interface OAuthApiInterface
{
    /**
     * @return string
     */
    public function getCSRFToken();

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function password($username, $password);

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function clientCredentials();
}
