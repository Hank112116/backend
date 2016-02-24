<?php namespace Backend\Repo\Lara;

use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Model\Eloquent\Feature;
use Backend\Model\Eloquent\Project;
use Backend\Repo\RepoInterfaces\SolutionInterface;
use Backend\Repo\RepoInterfaces\LandingFeatureInterface;

class LandingFeatureRepo implements LandingFeatureInterface
{
    private $feature;
    private $project;
    private $solution;
    private $user;

    public function __construct(Feature $feature, Project $project, SolutionInterface $solution, UserInterface $user)
    {
        $this->feature  = $feature;
        $this->project  = $project;
        $this->solution = $solution;
        $this->user     = $user;
    }

    public function all()
    {
        $features = $this->feature->all();
        foreach ($features as $f) {
            $f->entity = $this->entityByType($f->block_data, $f->block_type);
        }
        return $features;
    }

    public function byEntityIdType($id, $type)
    {
        $feature = new Feature();
        $feature->block_type = $type;
        $feature->block_data = $id;
        $feature->entity = $this->entityByType($id, $type);
        return $feature;
    }

    private function entityByType($id, $type)
    {
        $entity = null;
        switch ($type) {
            case 'project':
                $entity = $this->project->find($id);
                break;

            case 'solution':
                $entity = $this->solution->findSolution($id);
                break;

            case 'program':
                $entity = $this->solution->findProgram($id);
                break;

            case 'expert':
                $entity = $this->user->find($id);
                break;
        }

        return $entity;
    }

    public function types()
    {
        return $this->feature->types();
    }

    public function reset($features)
    {
        Feature::truncate();

        foreach ($features as $new_feature) {
            $f = new Feature();
            $f->fill($new_feature);
            $f->save();
        }
    }
}
