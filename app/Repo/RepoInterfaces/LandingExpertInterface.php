<?php namespace Backend\Repo\RepoInterfaces;

interface LandingExpertInterface
{
    public function getExpertList();
    public function getExpert($user_id);
    public function setExpert($data);
}
