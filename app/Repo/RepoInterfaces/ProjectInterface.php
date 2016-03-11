<?php namespace Backend\Repo\RepoInterfaces;

use Backend\Model\Eloquent\Project;
use Propel\Runtime\Collection\Collection;

interface ProjectInterface
{
    /**
     * @param $project_id
     * @return Project
     */
    public function find($project_id);

    /**
     * @return Collection|Project[]
     */
    public function all();

    /**
     * @return Collection|Project[]
     */
    public function deletedProjects();

    /**
     * @param $page
     * @param $limit
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function byPage($page, $limit);

    /**
     * @param $project_id
     * @return Collection|Project[]
     */
    public function byProjectId($project_id);

    /**
     * @param $name
     * @return Collection|Project[]
     */
    public function byUserName($name);

    /**
     * @param $title
     * @return Collection|Project[]
     */
    public function byTitle($title);

    /**
     * @param $from
     * @param $to
     * @return Collection|Project[]
     */
    public function byDateRange($from, $to);

    /**
     * @param $user_id
     * @return Collection|Project[]
     */
    public function byUserId($user_id);

    /**
     * @param $project_id
     * @param $data
     * @return boolean
     */
    public function update($project_id, $data);

    /**
     * @param $project
     * @return boolean
     */
    public function delete($project);

    /**
     * @return array
     */
    public function categoryOptions($is_selected = true);

    /**
     * @return array
     */
    public function currentStageOptions();

    /**
     * @return array
     */
    public function resourceOptions();

    /**
     * @return array
     */
    public function quantityOptions();

    /**
     * @return array
     */
    public function projectTagTree();

    /**
     * @param $projects
     * @return array
     */
    public function toOutputArray($projects);

    /**
     * @param $project_id
     * @return void
     */
    public function toDraft($project_id);

    /**
     * @param $project_id
     * @return void
     */
    public function toSubmittedPrivate($project_id);

    /**
     * @param $project_id
     * @return void
     */
    public function toSubmittedPublic($project_id);
}
