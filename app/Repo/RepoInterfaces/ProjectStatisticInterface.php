<?php namespace Backend\Repo\RepoInterfaces;

use Backend\Model\Eloquent\Project;
use Illuminate\Support\Collection;

interface ProjectStatisticInterface
{
    /**
     * @param Project $project
     * @return array
     */
    public function loadProjectStatistic(Project $project);

    /**
     * @param Collection $projects
     * @return array
     */
    public function loadProjectStatistics(Collection $projects);
}
