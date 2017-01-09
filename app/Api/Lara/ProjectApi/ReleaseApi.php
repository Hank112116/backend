<?php

namespace Backend\Api\Lara\ProjectApi;

use Backend\Api\ApiInterfaces\ProjectApi\ReleaseApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\URI\API\HWTrek\ProjectApiEnum;
use Backend\Model\Eloquent\Project;

class ReleaseApi extends BasicApi implements ReleaseApiInterface
{
    /**
     * {@inheritDoc}
     */
    public function releaseSchedule(Project $project)
    {
        $uri = str_replace('(:any)', $project->uuid, ProjectApiEnum::RELEASE);
        $url = $this->hwtrek_url . $uri;

        return $this->patch($url);
    }

    /**
     * {@inheritDoc}
     */
    public function staffRecommendExperts(Project $project)
    {
        $uri = str_replace('(:any)', $project->uuid, ProjectApiEnum::STAFF_RECOMMEND_EXPERTS);
        $url = $this->hwtrek_url . $uri;

        return $this->post($url);
    }
}
