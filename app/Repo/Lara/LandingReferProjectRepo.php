<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\Project;
use Backend\Model\Eloquent\ReferenceProject;
use Backend\Repo\RepoInterfaces\LandingReferProjectInterface;

class LandingReferProjectRepo implements LandingReferProjectInterface
{
    public function __construct(
        ReferenceProject $refer,
        Project $project
    ) {
        $this->refer = $refer;
        $this->project = $project;
    }

    public function all()
    {
        return $this->refer->orderBy('order', 'asc')->get();
    }

    public function byProjectId($project_id)
    {
        if (!$project  =  $this->project->find($project_id)) {
            return false;
        }

        $entity = new ReferenceProject();
        $entity->project_id = $project_id;
        $entity->url_project_title = "project_{$project_id}";
        $entity->project = $project;
        $entity->order   = 0;

        return $entity;
    }

    public function reset($refers)
    {
        ReferenceProject::truncate();
        foreach ($refers as $refer) {
            ReferenceProject::create($refer);
        }
    }
}
