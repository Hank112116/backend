<?php

namespace Backend\Http\Controllers;

use Backend\Repo\RepoInterfaces\LandingFeatureInterface;
use Backend\Repo\RepoInterfaces\LandingManufacturerInterface;
use Backend\Repo\RepoInterfaces\LandingReferProjectInterface;
use Input;
use Noty;
use Redirect;
use Response;

class LandingController extends BaseController
{

    protected $cert = 'front_page';

    public function __construct(
        LandingFeatureInterface $feature,
        LandingManufacturerInterface $manufacturer,
        LandingReferProjectInterface $refer
    )
    {
        $this->feature = $feature;
        $this->manu    = $manufacturer;
        $this->refer   = $refer;
    }

    public function showFeature()
    {
        return view('landing.feature')
            ->with('types', $this->feature->types())
            ->with('features', $this->feature->all());
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
}
