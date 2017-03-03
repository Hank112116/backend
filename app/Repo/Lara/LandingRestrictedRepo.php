<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\LowPrioritySolution;
use Backend\Model\Eloquent\LowPriorityProject;
use Backend\Model\Eloquent\LowPriorityUser;
use Backend\Repo\RepoInterfaces\LandingRestrictedInterface;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoInterfaces\SolutionInterface;
use Backend\Repo\RepoInterfaces\UserInterface;

class LandingRestrictedRepo implements LandingRestrictedInterface
{
    private $user;
    private $project;
    private $solution;
    private $user_repo;
    private $project_repo;
    private $solution_repo;

    public function __construct(
        LowPriorityUser $user,
        LowPriorityProject $project,
        LowPrioritySolution $solution,
        UserInterface $user_repo,
        ProjectInterface $project_repo,
        SolutionInterface $solution_repo
    ) {
        $this->user = $user;
        $this->project = $project;
        $this->solution = $solution;
        $this->user_repo = $user_repo;
        $this->project_repo = $project_repo;
        $this->solution_repo = $solution_repo;
    }

    public function getUsers()
    {
        $users = $this->user->with('user')->orderBy('user_id', 'desc')->get();

        $r = [];
        foreach ($users as $user) {
            $r[] = $user->user;
        }

        return collect($r);
    }

    public function getProjects()
    {
        $projects = $this->project->with('project')->orderBy('project_id', 'desc')->get();

        $r = [];
        foreach ($projects as $project) {
            $r[] = $project->project;
        }

        return collect($r);
    }

    public function getSolutions()
    {
        $solutions = $this->solution->with('solution')->orderBy('solution_id', 'desc')->get();

        $r = [];
        foreach ($solutions as $solution) {
            $r[] = $solution->solution;
        }

        return collect($r);
    }

    public function alreadyHasObject($id, $type)
    {
        $object = null;

        switch ($type) {
            case 'user':
                $object = $this->user->find($id);
                break;
            case 'project':
                $object = $this->project->find($id);
                break;
            case 'solution':
                $object = $this->solution->find($id);
                break;
        }
        return is_null($object) ? false : true;
    }

    public function addObject($id, $type)
    {
        $object = $this->findObject($id, $type);

        if ($object) {
            return $this->insertObject($id, $type);
        }

        return false;
    }

    public function revokeObject($id, $type)
    {
        switch ($type) {
            case 'user':
                return LowPriorityUser::destroy($id);
                break;
            case 'project':
                return LowPriorityProject::destroy($id);
                break;
            case 'solution':
                return LowPrioritySolution::destroy($id);
                break;
        }
        return false;
    }

    private function insertObject($id, $type)
    {
        switch ($type) {
            case 'user':
                $invisible_user = new LowPriorityUser();
                $invisible_user->user_id = $id;
                return $invisible_user->save();
                break;
            case 'project':
                $invisible_project = new LowPriorityProject();
                $invisible_project->project_id = $id;
                return $invisible_project->save();
                break;
            case 'solution':
                $invisible_solution = new LowPrioritySolution();
                $invisible_solution->solution_id = $id;
                return $invisible_solution->save();
                break;
        }
        return false;
    }

    private function findObject($id, $type)
    {
        $object = null;
        switch ($type) {
            case 'user':
                $object = $this->user_repo->find($id);
                break;
            case 'project':
                $object = $this->project_repo->find($id);
                break;
            case 'solution':
                $object = $this->solution_repo->find($id);
                break;
        }

        return $object;
    }
}
