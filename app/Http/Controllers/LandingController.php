<?php

namespace Backend\Http\Controllers;

use Backend\Enums\ObjectType;
use Backend\Facades\Log;
use Backend\Model\Plain\TagNode;
use Backend\Repo\RepoInterfaces\LandingFeatureInterface;
use Backend\Repo\RepoInterfaces\LandingExpertInterface;
use Backend\Repo\RepoInterfaces\LandingRestrictedInterface;
use Backend\Repo\RepoInterfaces\LogAccessHelloInterface;
use Illuminate\Http\Request;
use Noty;

class LandingController extends BaseController
{
    protected $cert = 'marketing';

    private $expert;
    private $feature;
    private $restricted;

    public function __construct(
        LandingFeatureInterface $feature,
        LandingExpertInterface $expert,
        LandingRestrictedInterface $restricted
    ) {
        parent::__construct();

        $this->feature    = $feature;
        $this->expert     = $expert;
        $this->restricted = $restricted;
    }

    public function showFeature()
    {
        return view('landing.feature')
            ->with('types', $this->feature->types())
            ->with('features', $this->feature->all());
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

    public function findFeatureEntity($type)
    {
        $id = $this->request->get('id');
        $feature = $this->feature->byEntityIdType($id, $type);
        if (!$feature->entity) {
            $res = [
                'status' => 'fail',
                'msg'    => "There is no {$type} with id {$id}"
            ];
        } else {
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
        die();
        $feature = $this->request->get('feature', []);

        $this->feature->reset($feature);

        Log::info('Update feature', $feature);

        Noty::success('Update features successful');

        return redirect()->action('LandingController@showFeature');
    }

    public function updateHelloRedirect(Request $request, LogAccessHelloInterface $repo)
    {
        $default = "signup?status=2";
        $repo->updateHelloDestination($request->get('destination'));

        return response()->json(['status' => 'success',]);
    }
    
    public function showExpert()
    {
        $experts = $this->expert->getExpertList();
        $types[] = "expert";
        return view('landing.expert')
            ->with('types', $types)
            ->with('experts', $experts);
    }

    public function findExpertEntity()
    {
        $id = $this->request->get('id');
        $user = $this->expert->getExpert($id);
        if (sizeof($user) >0) {
            $block = view('landing.expert-block')
                ->with('user', $user)
                ->with('description', '')
                ->render();
            $res   = ['status' => 'success', 'newBlock' => $block];
        } else {
            $res   = ['status' => 'fail', 'msg' => 'No Expert Id!'];
        }

        return response()->json($res);
    }

    public function updateExpert()
    {
        $data = [];
        $user = $this->request->get('user');
        $description = $this->request->get('description');
        if (sizeof($user) >0) {
            foreach ($user as $key => $row) {
                $data[$key]["user_id"] = $row;
                $data[$key]["description"] = $description[$key];
            }
            $this->expert->setExpert($data);
        } else {
            $this->expert->setExpert($data);
        }

        Log::info('Update expert list of home page', $data);

        $res   = ['status' => 'success'];
        return response()->json($res);
    }
}
