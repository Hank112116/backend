<?php

namespace Backend\Api\ApiInterfaces\ProjectApi;

use Backend\Model\Eloquent\Project;

interface ReleaseApiInterface
{
    /**
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function releaseSchedule(Project $project);

    /**
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function staffRecommendExperts(Project $project);
}
