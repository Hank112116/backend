<?php namespace Backend\Repo\RepoTrait;

use Carbon;

trait TimeProcessTrait
{
    protected $default_datetime = '0000-00-00 00:00:00';
    protected function toDateString($datetime)
    {
        if ($datetime == $this->default_datetime) {
            return '';
        }

        return Carbon::parse($datetime)->toDateString();
    }

    protected function toDateTimeString($datetime)
    {
        if ($datetime == $this->default_datetime) {
            return '';
        }

        return Carbon::parse($datetime)->format('Y-m-d h:m');
    }
}
