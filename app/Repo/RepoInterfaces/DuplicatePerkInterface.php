<?php namespace Backend\Repo\RepoInterfaces;

interface DuplicatePerkInterface
{
    public function updateDuplicateProjectPerks($project_id, $data);
    public function coverPerks($project_id);
}
