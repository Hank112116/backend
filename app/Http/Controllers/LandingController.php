<?php

namespace Backend\Http\Controllers;

use Backend\Repo\RepoInterfaces\LandingFeatureInterface;
use Backend\Repo\RepoInterfaces\LandingExpertInterface;
use Backend\Repo\RepoInterfaces\LandingManufacturerInterface;
use Backend\Repo\RepoInterfaces\LandingReferProjectInterface;
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

    protected $cert = 'front_page';

    public function __construct(
        LandingFeatureInterface $feature,
        LandingManufacturerInterface $manufacturer,
        LandingReferProjectInterface $refer,
        LandingExpertInterface $expert
    ) {
        $this->feature = $feature;
        $this->manu    = $manufacturer;
        $this->refer   = $refer;
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

        if (!$feature = $this->feature->byEntityIdType($id, $type)) {
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

    public function showManufacturer()
    {
        return view('landing.manufacturer')
            ->with('manufacturers', $this->manu->all());
    }

    public function updateManufacturer()
    {
        $this->manu->reset(Input::all());
        Noty::success('Update manufacturers successful');

        return Redirect::action('LandingController@showManufacturer');
    }

    public function getNewManufacturer()
    {
        return view('landing.manufacturer-block')
            ->with('manufacturer', $this->manu->dummy());
    }

    public function showReferenceProject()
    {
        return view('landing.refer')
            ->with('refers', $this->refer->all());
    }

    public function findReferenceProject()
    {
        $id = Input::get('id');

        if (!$entity = $this->refer->byProjectId($id)) {
            $res = [
                'status' => 'fail',
                'msg'    => "There is no project with id {$id}"
            ];
        } else {
            $block = view('landing.refer-block')
                ->with('refer', $entity)
                ->with('project', $entity->project)
                ->render();

            $res = [
                'status'    => 'success',
                'new_block' => $block
            ];
        }

        return Response::json($res);
    }

    public function updateReferenceProject()
    {
        $this->refer->reset(Input::get('refer', []));
        Noty::success('Update refers successful');

        return Redirect::action('LandingController@showReferenceProject');
    }

    public function updateHelloRedirect(Request $request, LogAccessHelloInterface $repo)
    {
        $default = "signup?status=2";
        $repo->updateHelloDestination($request->get('destination', $default));

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
                ->render();
            $res   = ['status' => 'success', 'new_block' => $block];
        } else {
            $res   = ['status' => 'fail', "msg" => "No Expert Id!"];
        }

        return Response::json($res);
    }
    public function updateExpert()
    {
        $sort = Input::get('sort');
        $this->expert->setExpert($sort);
        $res   = ['status' => 'success'];
        return Response::json($res);
    }
}
