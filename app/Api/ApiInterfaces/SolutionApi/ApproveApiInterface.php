<?php

namespace Backend\Api\ApiInterfaces\SolutionApi;

interface ApproveApiInterface
{
    /**
     * @return mixed
     */
    public function approve();

    /**
     * @return mixed
     */
    public function reject();
}
