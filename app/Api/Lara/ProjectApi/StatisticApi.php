<?php

namespace Backend\Api\Lara\ProjectApi;

use Backend\Api\ApiInterfaces\ProjectApi\StatisticApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\URI\API\HWTrek\ProjectApiEnum;
use Illuminate\Support\Collection;

class StatisticApi extends BasicApi implements StatisticApiInterface
{
    private $projects;

    public function __construct(Collection $projects)
    {
        parent::__construct();
        $this->projects = $projects;
    }

    /**
     * {@inheritDoc}
     */
    public function load()
    {
        $project_ids = [];
        foreach ($this->projects as $project) {
            $project_ids[] = $project->project_id;
        }
        $url = $this->hwtrek_url . ProjectApiEnum::PROJECT_STATISTICS . '?projectIds=' . json_encode($project_ids);

        return $this->get($url);
    }
}
