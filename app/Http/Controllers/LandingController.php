<?php

namespace Backend\Http\Controllers;

use Backend\Repo\RepoInterfaces\LandingFeatureInterface;
use Backend\Repo\RepoInterfaces\LandingExpertInterface;
use Backend\Repo\RepoInterfaces\LogAccessHelloInterface;
use Guzzle\Log\LogAdapterInterface;
use Illuminate\Http\Request;
use Input;
use Noty;
use Redirect;
use Response;
use Browser\Browser;

class LandingController extends BaseController
{

    protected $cert = 'marketing';

    public function __construct(
        LandingFeatureInterface $feature,
        LandingExpertInterface $expert
    ) {
        $this->feature = $feature;
        $this->expert  = $expert;
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

    public function findFeatureEntity($type)
    {
        $id = Input::get('id');
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

        return Response::json($res);
    }

    public function updateFeature()
    {
        $this->feature->reset(Input::get('feature', []));
        Noty::success('Update features successful');

        return Redirect::action('LandingController@showFeature');
    }

    public function updateHelloRedirect(Request $request, LogAccessHelloInterface $repo)
    {
        $default = "signup?status=2";
        $repo->updateHelloDestination($request->get('destination'));

        return Response::json(['status' => 'success',]);
    }
    public function showExpert()
    {
        $experts = $this->expert->getExpertList();
        $types[] = "expert";
        return view('landing.expert')
            ->with('types', $types)
            ->with('experts', $experts);
    }
    public function findExpertEntity($type)
    {
        $id = Input::get('id');
        $user = $this->expert->getExpert($id);
        if (sizeof($user) >0) {
            $block = view('landing.expert-block')
                ->with('user', $user[0])
                ->with('description', "")
                ->render();
            $res   = ['status' => 'success', 'newBlock' => $block];
        } else {
            $res   = ['status' => 'fail', "msg" => "No Expert Id!"];
        }

        return Response::json($res);
    }
    public function updateExpert()
    {
        $data = [];
        $user = Input::get('user');
        $description = Input::get('description');
        if (sizeof($user) >0) {
            foreach ($user as $key => $row) {
                $data[$key]["user_id"] = $row;
                $data[$key]["description"] = $description[$key];
            }
            $this->expert->setExpert($data);
        } else {
            $this->expert->setExpert($data);
        }
        $res   = ['status' => 'success'];
        return Response::json($res);
    }
}
