<?php namespace Backend\Repo\RepoInterfaces;

interface LandingFeatureInterface
{
    public function all();
    public function types();
    public function byEntityIdType($id, $type);
    public function reset($features);
}
