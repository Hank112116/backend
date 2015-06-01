<?php namespace Backend\Repo\RepoInterfaces;

interface LandingExpertInterface
{
    public function get_expert_list();
    public function get_expert($user_id);
    public function set_expert($sort);
}
