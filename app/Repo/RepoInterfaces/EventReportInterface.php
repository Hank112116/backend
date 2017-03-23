<?php namespace Backend\Repo\RepoInterfaces;

interface EventReportInterface
{
    public function getEventReport($event_id, $input, $page, $per_page);
    public function getQuestionnaireReport($event_id, $input, $page, $per_page);
}
