<?php

namespace Backend\Api\ApiInterfaces\AuthApi;

interface OAuthApiInterface
{
    /**
     * @param $username
     * @param $password
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function password($username, $password);

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function clientCredentials();
}
