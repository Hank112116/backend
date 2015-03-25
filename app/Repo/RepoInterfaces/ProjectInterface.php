<?php namespace Backend\Repo\RepoInterfaces;

interface ProjectInterface
{
    public function find($project_id);
    public function all();
    public function deletedProjects();

    public function byPage($page, $limit);
    public function byProjectId($project_id);
    public function byUserName($name);
    public function byTitle($title);
    public function byDateRange($from, $to);

    public function update($project_id, $data);

    public function categoryOptions();
    public function currentStageOptions();
    public function resourceOptions();
    public function quantityOptions();

    public function projectTagTree();
    public function toOutputArray($projects);

    public function toDraft($project_id);
    public function toSubmittedPrivate($project_id);
    public function toSubmittedPublic($project_id);
}
