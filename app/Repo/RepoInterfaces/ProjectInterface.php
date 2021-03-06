<?php namespace Backend\Repo\RepoInterfaces;

use Backend\Model\Eloquent\Project;
use Illuminate\Database\Eloquent\Collection;

interface ProjectInterface
{
    /**
     * @param $project_id
     * @return Project
     */
    public function find($project_id);

    /**
     * @param $project_id
     * @return Project
     */
    public function findOngoingProject($project_id);

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
     * @param array $input
     * @return Collection|Project[]
     */
    public function byUnionSearch($input, $page, $per_page, $do_statistics = false);

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
    public function categoryOptions();

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
    public function teamSizeOptions();

    /**
     * @return array
     */
    public function budgetOptions();

    /**
     * @return array
     */
    public function innovationOptions();

    /**
     * @return array
     */
    public function projectTagTree();

    /**
     * @return array
     */
    public function tagTree();

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

    /**
     * @param $project_id
     * @param $data
     * @return boolean
     */
    public function updateNote($project_id, $data);

    /**
     * @param $project_id
     * @param $data
     * @return boolean
     */
    public function updateInternalNote($project_id, $data);

    /**
     * @param $project_id
     * @param $data
     * @return boolean
     */
    public function updateProjectManager($project_id, $data);

    /**
     * @return int
     */
    public function getNotRecommendExpertProjectCount();
}
