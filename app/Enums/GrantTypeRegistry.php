<?php

namespace Backend\Enums;

class GrantTypeRegistry
{
    const AUTH_CODE          = 'authorization_code';
    const CLIENT_CREDENTIALS = 'client_credentials';
    const IMPLICIT           = 'implicit';  // 'implicit' not in RFC
    const PASSWORD           = 'password';
    const REFRESH_TOKEN      = 'refresh_token';
}
