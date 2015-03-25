<?php namespace Backend\Repo\RepoInterfaces;

interface TransactionInterface
{
    public function all();
    public function byPage($page, $limit);
    public function byUserName($name);
    public function byProjectId($project_id);
    public function byProjectTitle($title);
    public function byDateRange($from, $to);
}
