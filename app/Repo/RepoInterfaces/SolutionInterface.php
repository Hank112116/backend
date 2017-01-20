<?php namespace Backend\Repo\RepoInterfaces;

use Backend\Model\Eloquent\DuplicateSolution;
use Backend\Model\Eloquent\Solution;
use Illuminate\Database\Eloquent\Collection;

interface SolutionInterface
{
    /**
     * @return Collection|Solution[]
     */
    public function all();

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
     * @return array
     */
    public function updateImageGalleries($id, $data);

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
     * @param $solutions
     * @return array
     */
    public function toOutputArray($solutions);

    /**
     * @param $solutions
     * @return mixed
     */
    public function configApprove($solutions);
}
