<?php namespace Backend\Repo\RepoInterfaces;

interface ReportInterface
{
    public function getCommentReport($filter, $input, $page, $per_page);
    public function getRegistrationReport($filter, $input, $page, $per_page);
    public function getEventReport($event_id, $complete, $input, $page, $per_page);
}
