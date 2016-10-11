<?php

namespace Backend\Api\ApiInterfaces\ProjectApi;

interface ReleaseApiInterface
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function releaseSchedule();

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function staffRecommendExperts();
}
