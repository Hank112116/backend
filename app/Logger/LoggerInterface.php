<?php namespace Backend\Logger;

interface LoggerInterface
{
    public function error($env, $msg);
}
