<?php
namespace Backend\Assistant\ApiResponse\MarketingApi;

use Backend\Assistant\ApiResponse\BaseResponseAssistant;
use Backend\Model\Feature\FeatureEntity;
use Backend\Model\Feature\FeatureNormalizer;
use Backend\Model\Feature\FeatureStatistics;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class FeatureResponseAssistant extends BaseResponseAssistant
{
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public static function create(Response $response)
    {
        return new FeatureResponseAssistant($response);
    }

    /**
     * @return Collection
     */
    public function getFeatures()
    {
        $collection = Collection::make();

        if (!$this->response->isOk()) {
            return $collection;
        }

        $features = $this->decode();

        foreach ($features as $feature) {
            $collection->push(FeatureEntity::denormalize($feature));
        }

        return $collection;
    }

    public function normalizeEntity($entity)
    {
        $normalizer = new FeatureNormalizer();
    }
}
