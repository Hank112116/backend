<?php
namespace Backend\Logger;

use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use Monolog\Logger;

use Auth;
use Request;

class LogService implements LoggerInterface
{
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        switch ($level) {
            case LogLevel::EMERGENCY:
                $this->emergency($message, $context);
                break;
            case LogLevel::ALERT:
                $this->alert($message, $context);
                break;
            case LogLevel::CRITICAL:
                $this->critical($message, $context);
                break;
            case LogLevel::ERROR:
                $this->error($message, $context);
                break;
            case LogLevel::WARNING:
                $this->warning($message, $context);
                break;
            case LogLevel::NOTICE:
                $this->notice($message, $context);
                break;
            case LogLevel::INFO:
                $this->info($message, $context);
                break;
            case LogLevel::DEBUG:
                $this->debug($message, $context);
                break;
        }
    }

    /**
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function emergency($message, array $context = [])
    {
        $this->logger->emergency($message, array_merge($this->getBaseContext(), $context));
    }

    /**
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function alert($message, array $context = [])
    {
        $this->logger->alert($message, array_merge($this->getBaseContext(), $context));
    }

    /**
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function critical($message, array $context = [])
    {
        $this->logger->critical($message, array_merge($this->getBaseContext(), $context));
    }

    /**
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function error($message, array $context = [])
    {
        $this->logger->error($message, array_merge($this->getBaseContext(), $context));
    }

    /**
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function warning($message, array $context = [])
    {
        $this->logger->warning($message, array_merge($this->getBaseContext(), $context));
    }

    /**
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function notice($message, array $context = [])
    {
        $this->logger->notice($message, array_merge($this->getBaseContext(), $context));
    }

    /**
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function info($message, array $context = [])
    {
        $this->logger->info($message, array_merge($this->getBaseContext(), $context));
    }

    /**
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function debug($message, array $context = [])
    {
        $this->logger->debug($message, $context);
    }

    /*
     * return base log information
     *
     * @return array
     */
    private function getBaseContext()
    {
        $base_info = [
            'adminer_id' => Auth::id(),
            'user_agent' => Request::server('HTTP_USER_AGENT'),
            'user_ip'    => Request::getClientIp(),
            'referer'    => Request::server('HTTP_REFERER'),
        ];

        return $base_info;
    }
}
