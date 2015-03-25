<?php namespace Backend\Repo\RepoInterfaces;

interface DuplicateSolutionInterface
{
    public function find($solution_id);
    public function waitApproveSolutionIds();
    public function waitApproveSolutions();
    public function approve($id, $is_manager);
    public function reject($id);
    public function update($id, $data);
}
