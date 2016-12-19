<?php

namespace Backend\Api\Lara\ProjectApi;

use Backend\Api\ApiInterfaces\ProjectApi\StatisticApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\URI\API\HWTrek\ProjectApiEnum;
use Illuminate\Support\Collection;

class StatisticApi extends BasicApi implements StatisticApiInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(Collection $projects)
    {
        $project_ids = [];
        foreach ($projects as $project) {
            $project_ids[] = $project->project_id;
        }
        $url = $this->hwtrek_url . ProjectApiEnum::PROJECT_STATISTICS . '?projectIds=' . json_encode($project_ids);

        return $this->get($url);
    }
}
