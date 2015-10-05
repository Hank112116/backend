<?php

namespace Backend\Facades;

use Illuminate\Support\Facades\Facade;
use Psr\Log\LoggerInterface;

/**
 * Facade for PSR3 LoggerInterface
 *
 * @see \Psr\Log\LoggerInterface
 *
 * @method static null emergency($message, array $context = [])
 * @method static null alert($message, array $context = [])
 * @method static null critical($message, array $context = [])
 * @method static null error($message, array $context = [])
 * @method static null warning($message, array $context = [])
 * @method static null notice($message, array $context = [])
 * @method static null info($message, array $context = [])
 * @method static null debug($message, array $context = [])
 * @method static null log($level, $message, array $context = [])
 */
class Log extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return LoggerInterface::class;
    }
}
