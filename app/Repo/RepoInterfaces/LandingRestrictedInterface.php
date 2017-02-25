<?php namespace Backend\Repo\RepoInterfaces;

use Backend\Model\Eloquent\Project;
use Backend\Model\Eloquent\Solution;
use Backend\Model\Eloquent\User;
use Illuminate\Support\Collection;

interface LandingRestrictedInterface
{
    /**
     * @return Collection|User[]
     */
    public function getUsers();

    /**
     * @return Collection|Project[]
     */
    public function getProjects();

    /**
     * @return Collection|Solution[]
     */
    public function getSolutions();

    /**
     * @param $id
     * @param $type
     * @return bool
     */
    public function alreadyHasObject($id, $type);

    /**
     * @param $id
     * @param $type
     * @return bool
     */
    public function addObject($id, $type);

    /**
     * @param $id
     * @param $type
     * @return bool
     */
    public function revokeObject($id, $type);
}
