<?php

namespace Backend\Api\Lara\ProjectApi;

use Backend\Api\ApiInterfaces\ProjectApi\ReleaseApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\URI\API\HWTrek\ProjectApiEnum;
use Backend\Model\Eloquent\Project;

class ReleaseApi extends BasicApi implements ReleaseApiInterface
{
    private $project;

    public function __construct(Project $project)
    {
        parent::__construct();
        $this->project = $project;
    }

    /**
     * {@inheritDoc}
     */
    public function releaseSchedule()
    {
        $uri = str_replace('(:any)', $this->project->uuid, ProjectApiEnum::RELEASE);
        $url = $this->hwtrek_url . $uri;

        return $this->patch($url);
    }

    /**
     * {@inheritDoc}
     */
    public function staffRecommendExperts()
    {
        $uri = str_replace('(:any)', $this->project->uuid, ProjectApiEnum::STAFF_RECOMMEND_EXPERTS);
        $url = $this->hwtrek_url . $uri;

        return $this->post($url);
    }
}
