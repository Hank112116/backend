<?php

namespace Backend\Api\ApiInterfaces\ProjectApi;

interface StatisticApiInterface
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function load();
}
