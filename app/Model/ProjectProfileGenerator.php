<?php namespace Backend\Model;

use Backend\Model\Eloquent\Project;
use Backend\Model\Plain\ProjectProfile;
use Backend\Model\ModelInterfaces\ProjectProfileGeneratorInterface;

class ProjectProfileGenerator implements ProjectProfileGeneratorInterface
{
    /*
     * return ProjectProfile
     */
    public function gen(Project $project)
    {
        $profile = new ProjectProfile();
        $profile->setProject($project);

        return $profile;
    }
}
