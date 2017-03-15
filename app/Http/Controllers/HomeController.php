<?php

namespace Backend\Http\Controllers;

use Backend\Assistant\ApiResponse\MarketingApi\FeatureResponseAssistant;

class HomeController extends BaseController
{
    public function index()
    {
        $api = app()->make(\Backend\Api\ApiInterfaces\MarketingApi\FeatureApiInterface::class);
        $a = [
            0 =>
                [
                    'objectType' => 'expert',
                    'objectId' => '1119',
                    'order' => 1,
                ],
            1 =>
                [
                    'objectType' => 'solution',
                    'objectId' => '514',
                    'order' => 2,
                ],
            2 =>
                [
                    'objectType' => 'project',
                    'objectId' => '880',
                    'order' => 3,
                ],
            3 =>
                [
                    'objectType' => 'solution',
                    'objectId' => '587',
                    'order' => 4,
                ],
            4 =>
                [
                    'objectType' => 'expert',
                    'objectId' => '6',
                    'order' => 5,
                ],
            5 =>
                [
                    'objectType' => 'solution',
                    'objectId' => '624',
                    'order' => 6,
                ],
            6 =>
                [
                    'objectType' => 'expert',
                    'objectId' => '15',
                    'order' => 7,
                ],
            7 =>
                [
                    'objectType' => 'solution',
                    'objectId' => '793',
                    'order' => 8,
                ],
            8 =>
                [
                    'objectType' => 'solution',
                    'objectId' => '771',
                    'order' => 9,
                ],
            9 =>
                [
                    'objectType' => 'expert',
                    'objectId' => '1222',
                    'order' => 10,
                ],
        ];

        $feature_assistant = FeatureResponseAssistant::create($api->loadFeatures());

        /* @var \Backend\Model\Feature\FeatureEntity $feature */
        foreach ($feature_assistant->getFeatures() as $feature) {
            var_dump($feature->getObjectId());
            var_dump($feature->getTextObjectType());
        }

        return view('home.home');
    }
}
