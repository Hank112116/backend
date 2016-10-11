<?php

namespace Backend\Api\Lara\SolutionApi;

use Backend\Api\ApiInterfaces\SolutionApi\ApproveApiInterface;
use Backend\Api\Lara\HWTrekApi;
use Backend\Enums\URI\API\HWTrek\SolutionApiEnum;
use Backend\Enums\URI\API\HWTrekApiEnum;
use Backend\Model\Eloquent\Solution;

class ApproveApi extends HWTrekApi implements ApproveApiInterface
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
        $uri = str_replace('(:num)', $this->solution->solution_id, SolutionApiEnum::APPROVE);
        $url = $this->hwtrek_url . $uri;
        $r = $this->patch($url);
        return $this->response((array) $r);
    }

    /**
     * {@inheritDoc}
     */
    public function reject()
    {
        $uri = str_replace('(:num)', $this->solution->solution_id, SolutionApiEnum::REJECT_APPROVE);
        $url = $this->hwtrek_url . $uri;
        $r = $this->patch($url);
        return $this->response((array) $r);
    }
}
