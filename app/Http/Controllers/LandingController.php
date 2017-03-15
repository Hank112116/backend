<?php

namespace Backend\Http\Controllers;

use Backend\Api\ApiInterfaces\MarketingApi\FeatureApiInterface;
use Backend\Assistant\ApiResponse\MarketingApi\FeatureResponseAssistant;
use Backend\Enums\ObjectType;
use Backend\Facades\Log;
use Backend\Model\Feature\FeatureAssistant;
use Backend\Model\Feature\FeatureEntity;
use Backend\Repo\RepoInterfaces\LandingFeatureInterface;
use Backend\Repo\RepoInterfaces\LandingRestrictedInterface;
use Backend\Repo\RepoInterfaces\LogAccessHelloInterface;
use Illuminate\Http\Request;
use Noty;

class LandingController extends BaseController
{
    protected $cert = 'marketing';

    private $feature;
    private $restricted;
    private $feature_assistant;

    public function __construct(
        LandingFeatureInterface $feature,
        LandingRestrictedInterface $restricted,
        FeatureAssistant $feature_assistant
    ) {
        parent::__construct();

        $this->feature = $feature;
        $this->restricted = $restricted;
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
        $users     = $this->restricted->getUsers();
        $projects  = $this->restricted->getProjects();
        $solutions = $this->restricted->getSolutions();

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

        if ($this->restricted->alreadyHasObject($id, $type)) {
            $res = ['status' => 'success'];
            return response()->json($res);
        }

        if (!$this->restricted->addObject($id, $type)) {
            $res = [
                'status' => 'fail',
                'msg'    => "There is no {$type} with id {$id}"
            ];
        } else {
            Log::info("Low priority list add {$type} id: {$id}");
            $res = ['status' => 'success'];
        }

        return response()->json($res);
    }

    public function removeRestrictedObject()
    {
        $id   = $this->request->get('id');
        $type = $this->request->get('type');

        if (!$this->restricted->revokeObject($id, $type)) {
            $res = [
                'status' => 'fail',
                'msg'    => "Revoke {$type} {$id} fail"
            ];
        } else {
            Log::info("Low priority list revoke {$type} id: {$id}");
            $res = ['status' => 'success'];
        }

        return response()->json($res);
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
