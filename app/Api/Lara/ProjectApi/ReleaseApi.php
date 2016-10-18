<?php

namespace Backend\Api\Lara\ProjectApi;

use Backend\Api\ApiInterfaces\ProjectApi\ReleaseApiInterface;
use Backend\Api\Lara\HWTrekApi;
use Backend\Enums\URI\API\HWTrek\ProjectApiEnum;
use Backend\Model\Eloquent\Project;

class ReleaseApi extends HWTrekApi implements ReleaseApiInterface
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
        $r   = $this->patch($url);
        return $this->response((array) $r);
    }

    /**
     * {@inheritDoc}
     */
    public function staffRecommendExperts()
    {
        $uri = str_replace('(:any)', $this->project->uuid, ProjectApiEnum::STAFF_RECOMMEND_EXPERTS);
        $url = $this->hwtrek_url . $uri;
        $r   = $this->post($url);
        return $this->response((array) $r);
    }
}
