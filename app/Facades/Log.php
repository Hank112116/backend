<?php

namespace Backend\Facades;

use Illuminate\Support\Facades\Facade;
use Psr\Log\LoggerInterface;

/**
 * Facade for PSR3 LoggerInterface
 *
 * @see Psr\Log\LoggerInterface
 */
class Log extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return Logger
     */
    protected static function getFacadeAccessor()
    {
        return LoggerInterface::class;
    }
}
