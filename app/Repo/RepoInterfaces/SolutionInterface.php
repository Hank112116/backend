<?php namespace Backend\Repo\RepoInterfaces;

use Backend\Model\Eloquent\DuplicateSolution;
use Backend\Model\Eloquent\Solution;
use Illuminate\Database\Eloquent\Collection;

interface SolutionInterface
{
    /**
     * @return DuplicateSolutionInterface
     */
    public function duplicateRepo();

    /**
     * @param $id
     * @return Solution
     */
    public function find($id);

    /**
     * @param $id
     * @return Solution
     */
    public function findSolution($id);

    /**
     * @param $id
     * @return Solution
     */
    public function findProgram($id);

    /**
     * @param $id
     * @return DuplicateSolution
     */
    public function findDuplicate($id);

    /**
     * @return Collection|Solution[]
     */
    public function drafts();

    /**
     * @param $page
     * @param $limit
     * @return \Illuminate\Pagination\LengthAwarePaginator|Solution[]
     */
    public function approvedSolutions($page, $limit);

    /**
     * @return mixed
     */
    public function waitApproveSolutions();

    /**
     * @return Collection|Solution[]
     */
    public function deletedSolutions();

    /**
     * @return Collection|Solution[]
     */
    public function program();

    /**
     * @return Collection|Solution[]
     */
    public function pendingProgram();

    /**
     * @return Collection|Solution[]
     */
    public function pendingSolution();

    /**
     * @return boolean
     */
    public function hasWaitApproveSolution();

    /**
     * @return boolean
     */
    public function hasWaitManagerApproveSolution();

    /**
     * @return boolean
     */
    public function hasProgram();

    /**
     * @return boolean
     */
    public function hasPendingProgram();

    /**
     * @return boolean
     */
    public function hasPendingSolution();

    /**
     * @param $name
     * @return Collection|Solution[]
     */
    public function byUserName($name);

    /**
     * @param $title
     * @return Collection|Solution[]
     */
    public function byTitle($title);

    /**
     * @return array
     */
    public function categoryOptions();

    /**
     * @return array
     */
    public function certificationOptions();

    /**
     * @param $id
     * @param $data
     * @return void
     */
    public function update($id, $data);

    /**
     * @param $id
     * @param $is_manager
     * @return void
     */
    public function approve($id, $is_manager);

    /**
     * @param $id
     * @param $is_manager
     * @return void
     */
    public function toProgram($id, $is_manager);

    /**
     * @param $id
     * @param $is_manager
     * @return void
     */
    public function toSolution($id, $is_manager);

    /**
     * @param $id
     * @return void
     */
    public function reject($id);

    /**
     * @param $id
     * @return void
     */
    public function onShelf($id);

    /**
     * @param $id
     * @return void
     */
    public function offShelf($id);

    /**
     * @param $solution_id
     * @return boolean
     */
    public function isWaitApproveOngoing($solution_id);

    /**
     * @param $solutions
     * @return array
     */
    public function toOutputArray($solutions);
}
