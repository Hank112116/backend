<?php namespace Backend\Model;

use Backend\Model\Eloquent\Project;
use Backend\Model\Plain\ProjectProfile;
use Backend\Model\ModelInterfaces\ProjectProfileGeneratorInterface;
use Backend\Repo\RepoInterfaces\DuplicateProductInterface;

class ProjectProfileGenerator implements ProjectProfileGeneratorInterface
{
    private $duplicate_repo;
    private $wait_approve_ongoing_ids = null;

    public function __construct(DuplicateProductInterface $duplicate)
    {
        $this->duplicate_repo = $duplicate;
    }

    /*
     * return ProjectProfile
     */
    public function gen(Project $project)
    {
        $profile = new ProjectProfile();
        $profile->setProject($project);
        $profile->setIsWaitApproveOngoing($this->isWaitApproveOngoing($project));

        return $profile;
    }

    public function isWaitApproveOngoing($project)
    {
        if (is_null($this->wait_approve_ongoing_ids)) {
            $this->wait_approve_ongoing_ids = $this->duplicate_repo->waitApproveProjectIds()->toArray();
        }
        return in_array($project->project_id, $this->wait_approve_ongoing_ids);
    }
}
