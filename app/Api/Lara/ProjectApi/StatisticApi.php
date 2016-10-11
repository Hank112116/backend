<?php

namespace Backend\Api\Lara\ProjectApi;

use Backend\Api\ApiInterfaces\ProjectApi\StatisticApiInterface;
use Backend\Api\Lara\HWTrekApi;
use Backend\Enums\URI\API\HWTrek\ProjectApiEnum;
use Backend\Model\Eloquent\Project;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class StatisticApi extends HWTrekApi implements StatisticApiInterface
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
        $uri = str_replace('(:any)', implode('+', $project_ids), ProjectApiEnum::PROJECT_STATISTICS);
        $url = $this->hwtrek_url . $uri;
        $r   = $this->get($url);
        return $this->response((array) $r);
    }
}
