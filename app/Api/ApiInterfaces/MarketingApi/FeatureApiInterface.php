<?php

namespace Backend\Api\ApiInterfaces\MarketingApi;

interface FeatureApiInterface
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadFeatures();

    /**
     * @param array $features
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateFeatures(array $features);
}
