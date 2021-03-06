<?php namespace Backend\Repo\RepoInterfaces;

interface ReportInterface
{
    public function getCommentReport($filter, $input, $page, $per_page);
    public function getRegistrationReport($filter, $input, $page, $per_page);
    public function getMemberMatchingReport($input, $page, $per_page);
    public function getDetailMatchingDataByUser($user_id, $dstart, $dend);
}
