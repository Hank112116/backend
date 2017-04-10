<?php

namespace Backend\Api\Lara\MarketingApi;

use Backend\Api\ApiInterfaces\MarketingApi\LowPriorityObjectApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\URI\API\HWTrek\MarketingApiEnum;

class LowPriorityObjectApi extends BasicApi implements LowPriorityObjectApiInterface
{
    /**
     * {@inheritDoc}
     */
    public function loadUsers()
    {
        $url = $this->hwtrek_url . MarketingApiEnum::LOW_PRIORITY_USERS;

        return $this->get($url);
    }

    /**
     * {@inheritDoc}
     */
    public function loadProjects()
    {
        $url = $this->hwtrek_url . MarketingApiEnum::LOW_PRIORITY_PROJECTS;

        return $this->get($url);
    }

    /**
     * {@inheritDoc}
     */
    public function loadSolutions()
    {
        $url = $this->hwtrek_url . MarketingApiEnum::LOW_PRIORITY_SOLUTIONS;

        return $this->get($url);
    }

    /**
     * {@inheritDoc}
     */
    public function addUser(int $user_id)
    {
        $url = $this->hwtrek_url . MarketingApiEnum::LOW_PRIORITY_USERS . '/' . $user_id;

        return $this->put($url);
    }

    /**
     * {@inheritDoc}
     */
    public function addProject(int $project_id)
    {
        $url = $this->hwtrek_url . MarketingApiEnum::LOW_PRIORITY_PROJECTS . '/' . $project_id;

        return $this->put($url);
    }

    /**
     * {@inheritDoc}
     */
    public function addSolution(int $solution_id)
    {
        $url = $this->hwtrek_url . MarketingApiEnum::LOW_PRIORITY_SOLUTIONS . '/' . $solution_id;

        return $this->put($url);
    }

    /**
     * {@inheritDoc}
     */
    public function revokeUser(int $user_id)
    {
        $url = $this->hwtrek_url . MarketingApiEnum::LOW_PRIORITY_USERS . '/' . $user_id;

        return $this->delete($url);
    }

    /**
     * {@inheritDoc}
     */
    public function revokeProject(int $project_id)
    {
        $url = $this->hwtrek_url . MarketingApiEnum::LOW_PRIORITY_PROJECTS . '/' . $project_id;

        return $this->delete($url);
    }

    /**
     * {@inheritDoc}
     */
    public function revokeSolution(int $solution_id)
    {
        $url = $this->hwtrek_url . MarketingApiEnum::LOW_PRIORITY_SOLUTIONS . '/' . $solution_id;

        return $this->delete($url);
    }
}
