<?php

namespace Backend\Api\Lara\SolutionApi;

use Backend\Api\ApiInterfaces\SolutionApi\ApproveApiInterface;
use Backend\Api\Lara\HWTrekApi;
use Backend\Enums\URI\API\HWTrekApiEnum;
use Backend\Model\Eloquent\Solution;

class ApproveApi extends HWTrekApi implements ApproveApiInterface
{
    private $solution;
    private $url;

    public function __construct(Solution $solution)
    {
        parent::__construct();
        $this->solution = $solution;
        $this->url      = 'https://' . $this->front_domain . HWTrekApiEnum::API . HWTrekApiEnum::SOLUTION . '/' . $solution->solution_id;
    }

    /**
     * {@inheritDoc}
     */
    public function approve()
    {
        $url = $this->url . HWTrekApiEnum::APPROVE;
        $r = $this->patch($url);
        return $this->response((array) $r);
    }

    /**
     * {@inheritDoc}
     */
    public function reject()
    {
        $url = $this->url . HWTrekApiEnum::REJECT_APPROVE;
        $r = $this->patch($url);
        return $this->response((array) $r);
    }
}
