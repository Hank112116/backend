<?php namespace Backend\Repo\RepoInterfaces;

interface SolutionInterface
{
    public function duplicateRepo();

    public function find($id);
    public function findDuplicate($id);

    public function drafts();
    public function approvedSolutions($page, $limit);
    public function waitApproveSolutions();
    public function deletedSolutions();

    public function hasWaitApproveSolution();
    public function hasWaitManagerApproveSolution();

    public function byUserName($name);
    public function byTitle($title);

    public function categoryOptions();
    public function certificationOptions();

    public function update($id, $data);

    public function approve($id, $is_manager);
    public function reject($id);

    public function onShelf($id);
    public function offShelf($id);

    public function isWaitApproveOngoing($solution_id);

    public function toOutputArray($solutions);
}
