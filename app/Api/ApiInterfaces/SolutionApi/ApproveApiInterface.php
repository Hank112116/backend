<?php

namespace Backend\Api\ApiInterfaces\SolutionApi;

use Backend\Model\Eloquent\Solution;

interface ApproveApiInterface
{
    /**
     * @param Solution $solution
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function approve(Solution $solution);

    /**
     * @param Solution $solution
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reject(Solution $solution);
}
