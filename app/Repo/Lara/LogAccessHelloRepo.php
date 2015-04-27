<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\LogAccessHello;
use Backend\Model\Eloquent\Misc;
use Backend\Repo\RepoInterfaces\LogAccessHelloInterface;

class LogAccessHelloRepo implements LogAccessHelloInterface
{
    private $log_access_hello;

    public function __construct(LogAccessHello $log_access_hello, Misc $misc)
    {
        $this->log_access_hello = $log_access_hello;
        $this->misc = $misc;
    }

    public function latest($take = 10)
    {
        return $this->log_access_hello->with('user')
            ->orderBy('log_id', 'DESC')
            ->take($take)->get();
    }

    public function updateHelloDestination($destination) {
        if(!$destination) {
            return;
        }

        $this->misc->insertOrUpdate('hello_destination', $destination);
    }
}
