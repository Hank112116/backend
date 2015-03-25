<?php namespace Backend\ErrorReport;

use Exception;

interface ReporterInterface
{
    public function send(Exception $e);
}
