<?php namespace Backend\Repo\RepoInterfaces;

interface ProductInterface
{
    public function find($project_id);
    public function findDuplicate($project_id);

    public function all();

    public function byPage($page, $limit);
    public function byUserId($user_id);
    public function byUserName($name);
    public function byProjectId($project_id);
    public function byTitle($title);
    public function byDateRange($from, $to);

    public function waitApproves();

    public function approve($project_id);
    public function reject($project_id);
    public function update($project_id, $data);

    public function duplicateRepo();

    public function isWaitApproveOngoing($project_id);
    public function hasWaitApproveProject();

    public function postpone($project_id);
    public function recoverPostpone($project_id);

    public function toOutputArray($projects);
}
