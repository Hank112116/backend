<?php namespace Backend\Repo\RepoInterfaces;

interface PerkInterface
{
    public function byProjectId($project_id);
    public function editablePerkIds();

    public function updateProjectPerks($project_id, $data);
    public function newEntity($is_pro);
}
