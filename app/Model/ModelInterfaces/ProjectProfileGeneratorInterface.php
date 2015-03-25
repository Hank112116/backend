<?php namespace Backend\Model\ModelInterfaces;

use Backend\Model\Eloquent\Project;

interface ProjectProfileGeneratorInterface
{
    public function gen(Project $project);
}
