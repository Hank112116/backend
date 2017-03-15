<?php

namespace Backend\Model\Feature;

/**
 * Class FeatureAssistant
 *
 * @author HankChang <hank.chang@hwtrek.com>
 */
class FeatureAssistant
{
    /**
     * @param $features
     * @return FeatureStatistics
     */
    public function getFeatureStatistics($features)
    {
        $feature_statistics = new FeatureStatistics();

        /* @var FeatureEntity $feature */
        foreach ($features as $feature) {
            switch ($feature->getObjectType()) {
                case 'expert':
                    $feature_statistics->countExpert();
                    break;
                case 'premium-expert':
                    $feature_statistics->countPremiumExpert();
                    break;
                case 'project':
                    $feature_statistics->countProject();
                    break;
                case 'normal-solution':
                    $feature_statistics->countSolution();
                    break;
                case 'program':
                    $feature_statistics->countProgram();
                    break;
            }
        }

        return $feature_statistics;
    }

    /**
     * @param $feature
     * @return array
     */
    public function getFeatureData($feature)
    {
        $normalizer = new FeatureNormalizer();

        return $normalizer->normalize($feature);
    }
}
