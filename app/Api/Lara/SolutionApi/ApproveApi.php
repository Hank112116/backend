<?php

namespace Backend\Api\Lara\SolutionApi;

use Backend\Api\ApiInterfaces\SolutionApi\ApproveApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\URI\API\HWTrek\SolutionApiEnum;
use Backend\Model\Eloquent\Solution;

class ApproveApi extends BasicApi implements ApproveApiInterface
{
    /**
     * {@inheritDoc}
     */
    public function approve(Solution $solution)
    {
        $uri = str_replace('(:num)', $solution->solution_id, SolutionApiEnum::PUBLICITY);
        $url = $this->hwtrek_url . $uri;

        return $this->post($url);
    }

    /**
     * {@inheritDoc}
     */
    public function reject(Solution $solution)
    {
        $uri = str_replace('(:num)', $solution->solution_id, SolutionApiEnum::PUBLICITY);
        $url = $this->hwtrek_url . $uri;

        return $this->delete($url);
    }
}
