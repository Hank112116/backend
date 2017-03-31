<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\Project;
use Backend\Model\Eloquent\Solution;
use Backend\Model\Eloquent\User;
use Backend\Model\Feature\FeatureStatistics;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Model\Eloquent\Feature;
use Backend\Repo\RepoInterfaces\SolutionInterface;
use Backend\Repo\RepoInterfaces\LandingFeatureInterface;

class LandingFeatureRepo implements LandingFeatureInterface
{
    private $feature;
    private $project;
    private $solution;
    private $user;

    public function __construct(
        Feature $feature,
        ProjectInterface $project,
        SolutionInterface $solution,
        UserInterface $user
    ) {
        $this->feature  = $feature;
        $this->project  = $project;
        $this->solution = $solution;
        $this->user     = $user;
    }

    public function all()
    {
        $features = $this->feature->all();

        $feature_statistics = new FeatureStatistics();

        foreach ($features as $f) {
            $f->entity = $this->findEntity($f->block_data, $f->block_type);

            switch (true) {
                case $f->entity instanceof User:
                    if ($f->entity->isBasicExpert()) {
                        $feature_statistics->countExpert();
                    }

                    if ($f->entity->isPremiumExpert()) {
                        $feature_statistics->countPremiumExpert();
                    }
                    break;
                case $f->entity instanceof Project:
                    $feature_statistics->countProject();
                    break;
                case $f->entity instanceof Solution:
                    if ($f->entity->isSolution()) {
                        $feature_statistics->countSolution();
                    }

                    if ($f->entity->isProgram()) {
                        $feature_statistics->countProgram();
                    }
                    break;
            }
        }

        $features->statistics = $feature_statistics;

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
                $entity = $this->project->findOngoingProject($id);
                break;
            case 'solution':
            case 'program':
                $entity = $this->solution->findSolution($id);
                break;
            case 'expert':
            case 'user':
                $entity = $this->user->findActiveExpert($id);
                break;
        }

        return $entity;
    }

    public function hasFeature($id, $type)
    {
        $feature = $this->feature->where('block_type', $type)
            ->where('block_data', $id)
            ->first();

        return empty($feature) ? false : true;
    }

    private function findEntity($id, $type)
    {
        $entity = null;
        switch ($type) {
            case 'project':
                $entity = $this->project->find($id);
                break;
            case 'solution':
            case 'program':
                $entity = $this->solution->find($id);
                break;
            case 'expert':
            case 'user':
                $entity = $this->user->find($id);
                break;
        }

        return $entity;
    }
}
