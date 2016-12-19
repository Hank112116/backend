<?php

namespace Backend\Api\ApiInterfaces\ProjectApi;

use Illuminate\Support\Collection;

interface StatisticApiInterface
{
    /**
     * @param Collection $projects
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function load(Collection $projects);
}
