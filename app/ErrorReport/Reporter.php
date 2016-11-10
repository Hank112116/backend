<?php namespace Backend\ErrorReport;

use Backend\Logger\LoggerInterface;
use Illuminate\Encryption\Encrypter;
use Exception;

class Reporter implements ReporterInterface
{
    public function __construct(Encrypter $encrypter, LoggerInterface $logger)
    {
        $this->encrypter = $encrypter;
        $this->logger = $logger;
    }

    public function send(Exception $e)
    {
        $encrypted = $this->encrypter->encrypt($e->__toString());
        $this->logger->error(app()->environment(), $encrypted);
    }

    public function decrypt($msg)
    {
        return $this->encrypter->decrypt($msg);
    }
}
