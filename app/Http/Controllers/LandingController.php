<?php

namespace Backend\Http\Controllers;

use Backend\Api\ApiInterfaces\MarketingApi\FeatureApiInterface;
use Backend\Api\ApiInterfaces\MarketingApi\LowPriorityObjectApiInterface;
use Backend\Assistant\ApiResponse\MarketingApi\FeatureResponseAssistant;
use Backend\Assistant\ApiResponse\MarketingApi\LowPriorityProjectsResponseAssistant;
use Backend\Assistant\ApiResponse\MarketingApi\LowPrioritySolutionsResponseAssistant;
use Backend\Assistant\ApiResponse\MarketingApi\LowPriorityUsersResponseAssistant;
use Backend\Enums\ObjectType;
use Backend\Model\Feature\FeatureAssistant;
use Backend\Model\Feature\FeatureEntity;
use Backend\Repo\RepoInterfaces\LandingFeatureInterface;
use Backend\Repo\RepoInterfaces\LogAccessHelloInterface;
use Illuminate\Http\Request;
use Noty;
use Log;

class LandingController extends BaseController
{
    protected $cert = 'marketing';

    private $feature;
    private $feature_assistant;

    public function __construct(
        LandingFeatureInterface $feature,
        FeatureAssistant $feature_assistant
    ) {
        parent::__construct();

        $this->feature           = $feature;
        $this->feature_assistant = $feature_assistant;
    }

    public function showFeature()
    {
        $api = app()->make(FeatureApiInterface::class);

        $feature_assistant = FeatureResponseAssistant::create($api->loadFeatures());

        $features = $feature_assistant->getFeatures();

        $feature_statistics =  $this->feature_assistant->getFeatureStatistics($features);

        return view('landing.feature')
            ->with('features', $features)
            ->with('feature_statistics', $feature_statistics);
    }

    public function showHello(LogAccessHelloInterface $repo)
    {
        return view('landing.hello')->with(['records' => $repo->latest()]);
    }

    public function showRestricted()
    {
        $api = app()->make(LowPriorityObjectApiInterface::class);

        $users     = LowPriorityUsersResponseAssistant::create($api->loadUsers())->users();
        $projects  = LowPriorityProjectsResponseAssistant::create($api->loadProjects())->projects();
        $solutions = LowPrioritySolutionsResponseAssistant::create($api->loadSolutions())->solutions();

        return view('landing.restricted')
            ->with('type_list', ObjectType::OBJECT_TYPE)
            ->with('users', $users)
            ->with('projects', $projects)
            ->with('solutions', $solutions);
    }

    public function findFeatureEntity()
    {
        $id   = $this->request->get('id');
        $type = $this->request->get('type');

        if ($this->feature->hasFeature($id, $type)) {
            $res = [
                'status' => 'fail',
                'msg'    => "Duplicate object of {$type} #{$id}."
            ];

            return response()->json($res);
        }

        $feature = $this->feature->byEntityIdType($id, $type);

        $feature = $this->feature_assistant->getFeatureData($feature);

        if (empty($feature)) {
            $res = [
                'status' => 'fail',
                'msg'    => "There is no {$type} with id {$id}"
            ];
        } else {
            $feature = FeatureEntity::denormalize($feature);

            $block = view('landing.feature-block')
                ->with('feature', $feature)
                ->render();
            $res   = ['status' => 'success', 'new_block' => $block];
        }

        return response()->json($res);
    }

    public function addRestrictedObject($type)
    {
        $id = $this->request->get('id');

        $api = app()->make(LowPriorityObjectApiInterface::class);

        switch ($type) {
            case ObjectType::USER_TYPE:
                return $api->addUser($id);
            case ObjectType::PROJECT_TYPE:
                return $api->addProject($id);
            case ObjectType::SOLUTION_TYPE:
                return $api->addSolution($id);
        }
        return response([], 400);
    }

    public function removeRestrictedObject()
    {
        $id   = $this->request->get('id');
        $type = $this->request->get('type');

        $api = app()->make(LowPriorityObjectApiInterface::class);

        switch ($type) {
            case ObjectType::USER_TYPE:
                return $api->revokeUser($id);
            case ObjectType::PROJECT_TYPE:
                return $api->revokeProject($id);
            case ObjectType::SOLUTION_TYPE:
                return $api->revokeSolution($id);
        }
        return response([], 400);
    }

    public function updateFeature()
    {
        $feature = $this->request->get('features', []);

        $feature = json_decode($feature, true);

        // remove duplicate values from multi array
        $feature = collect($feature);

        $feature = $feature->unique(function ($item) {
            return $item['objectType'] . $item['objectId'];
        });

        $api = app()->make(FeatureApiInterface::class);

        $api->updateFeatures($feature->toArray());

        Log::info('Update feature', $feature->all());

        Noty::success('Update features successful');

        return redirect()->action('LandingController@showFeature');
    }

    public function updateHelloRedirect(Request $request, LogAccessHelloInterface $repo)
    {
        //$default = "signup?status=2";
        $repo->updateHelloDestination($request->get('destination'));

        return response()->json(['status' => 'success',]);
    }
}
