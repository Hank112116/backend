<?php

namespace Backend\Exceptions\Api;

use RuntimeException;
use Throwable;

class CompanyLogoApiException extends RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
