<?php

namespace Backend\Model\Feature;

use Backend\Model\Eloquent\Project;
use Backend\Model\Eloquent\Solution;
use Backend\Model\Eloquent\User;

/**
 * Class FeatureNormalizer
 *
 * @author HankChang <hank.chang@hwtrek.com>
 */
class FeatureNormalizer
{
    /**
     * @param $feature
     * @return array
     */
    public function normalize($feature)
    {
        $entity = $feature->entity;

        switch (true) {
            case $feature->entity instanceof User:
                $result['objectType'] = 'user';
                $result['user']       = $this->normalizeUser($entity);

                return $result;
                break;
            case $entity instanceof Project:
                $result['objectType'] = 'project';
                $result['project']       = $this->normalizeProject($entity);

                return $result;
                break;
            case $entity instanceof Solution:
                $result['objectType'] = 'solution';
                $result['solution']       = $this->normalizeSolution($entity);

                return $result;
                break;
        }
        return [];
    }

    /**
     * @param User $user
     * @return array
     */
    private function normalizeUser(User $user)
    {
        return [
            'id'          => $user->id(),
            'fullName'    => $user->textFullName(),
            'userType'    => $user->user_type,
            'companyName' => $user->company,
            'userUrl'     => $user->textFullName(),
            'status'      => $user->getStatus()
        ];
    }

    /**
     * @param Project $project
     * @return array
     */
    private function normalizeProject(Project $project)
    {
        /* @var User $owner */
        $owner = $project->user;

        return [
            'id'         => $project->id(),
            'name'       => $project->textTitle(),
            'status'     => $project->getStatus(),
            'projectUrl' => $project->textFrontLink(),
            'owner'      => [
                'fullName' => $owner->textFullName(),
                'userUrl'  => $owner->textFrontLink()
            ]
        ];
    }

    /**
     * @param Solution $solution
     * @return array
     */
    private function normalizeSolution(Solution $solution)
    {
        /* @var User $owner */
        $owner = $solution->user;

        return [
            'id'          => $solution->id(),
            'name'        => $solution->textTitle(),
            'status'      => $solution->getStatus(),
            'type'        => $solution->getType(),
            'solutionUrl' => $solution->textFrontLink(),
            'owner'       => [
                'fullName' => $owner->textFullName(),
                'userUrl'  => $owner->textFrontLink()
            ]
        ];
    }
}
