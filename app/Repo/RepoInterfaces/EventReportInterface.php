<?php namespace Backend\Repo\RepoInterfaces;

interface EventReportInterface
{
    /**
     * @param $event_id
     * @param $input
     * @param $page
     * @param $per_page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getEventReport($event_id, $input, $page, $per_page);

    /**
     * @param $event_id
     * @param $input
     * @param $page
     * @param $per_page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getQuestionnaireReport($event_id, $input, $page, $per_page);
}
