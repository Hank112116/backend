<?php

namespace Backend\Api\Lara\MarketingApi;

use Backend\Api\ApiInterfaces\MarketingApi\FeatureApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\URI\API\HWTrek\MarketingApiEnum;

class FeatureApi extends BasicApi implements FeatureApiInterface
{
    /**
     * {@inheritDoc}
     */
    public function loadFeatures()
    {
        $url = $this->hwtrek_url . MarketingApiEnum::FEATURES;

        return $this->get($url);
    }

    /**
     * {@inheritDoc}
     */
    public function updateFeatures(array $features)
    {
        $url = $this->hwtrek_url . MarketingApiEnum::FEATURES;

        $data = [
            'json' => $features
        ];

        return $this->put($url, $data);
    }
}
