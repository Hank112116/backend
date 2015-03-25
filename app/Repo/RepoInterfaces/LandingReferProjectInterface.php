<?php namespace Backend\Repo\RepoInterfaces;

interface LandingReferProjectInterface
{
    public function all();
    public function byProjectId($project_id);
    public function reset($refers);
}
