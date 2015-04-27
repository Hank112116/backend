<?php namespace Backend\Repo\RepoInterfaces;

interface LogAccessHelloInterface
{
    public function latest($take = 10);
    public function updateHelloDestination($destination);
}
