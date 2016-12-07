<?php

namespace Backend\Api\Lara\SolutionApi;

use Backend\Api\ApiInterfaces\SolutionApi\ApproveApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\URI\API\HWTrek\SolutionApiEnum;
use Backend\Model\Eloquent\Solution;

class ApproveApi extends BasicApi implements ApproveApiInterface
{
    private $solution;

    public function __construct(Solution $solution)
    {
        parent::__construct();
        $this->solution = $solution;
    }

    /**
     * {@inheritDoc}
     */
    public function approve()
    {
        $uri = str_replace('(:num)', $this->solution->solution_id, SolutionApiEnum::PUBLICITY);
        $url = $this->hwtrek_url . $uri;

        return $this->post($url);
    }

    /**
     * {@inheritDoc}
     */
    public function reject()
    {
        $uri = str_replace('(:num)', $this->solution->solution_id, SolutionApiEnum::PUBLICITY);
        $url = $this->hwtrek_url . $uri;

        return $this->delete($url);
    }
}
