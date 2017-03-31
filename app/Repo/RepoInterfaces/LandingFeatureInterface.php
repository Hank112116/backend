<?php namespace Backend\Repo\RepoInterfaces;

interface LandingFeatureInterface
{
    public function all();
    public function hasFeature($id, $type);
    public function byEntityIdType($id, $type);
}
