<?php namespace Backend\Repo\RepoInterfaces;

interface DuplicateProductInterface
{
    public function find($project_id);
    public function waitApproveProjectIds();

    public function approve($project_id, $approve);
    public function update($project_id, $data);
}
