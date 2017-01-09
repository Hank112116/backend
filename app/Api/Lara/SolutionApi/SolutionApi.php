<?php

namespace Backend\Api\Lara\SolutionApi;

use Backend\Api\ApiInterfaces\SolutionApi\ApproveApiInterface;
use Backend\Api\ApiInterfaces\SolutionApi\SolutionApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\API\Response\Key\SolutionKey;
use Backend\Enums\URI\API\HWTrek\SolutionApiEnum;
use Backend\Model\Eloquent\Solution;

class SolutionApi extends BasicApi implements SolutionApiInterface
{
    /**
     * {@inheritDoc}
     */
    public function listSolutions($query = null)
    {
        $url = $this->hwtrek_url . SolutionApiEnum::SOLUTIONS;

        if (!is_null($query)) {
            $url = $url . '?' . http_build_query($query);
        }

        return $this->get($url);
    }

    /**
     * {@inheritDoc}
     */
    public function getSolution(int $solution_id)
    {
        $uri = str_replace('(:num)', $solution_id, SolutionApiEnum::SOLUTION);
        $url = $this->hwtrek_url . $uri;

        return $this->get($url);
    }

    /**
     * {@inheritDoc}
     */
    public function changeToNormalSolution(int $solution_id)
    {
        $uri = str_replace('(:num)', $solution_id, SolutionApiEnum::TYPE);
        $url = $this->hwtrek_url . $uri;

        $data = [
            'json' => [
                'type' => 'normal-solution'
            ]
        ];

        return $this->patch($url, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function changeToProgram(int $solution_id)
    {
        $uri = str_replace('(:num)', $solution_id, SolutionApiEnum::TYPE);
        $url = $this->hwtrek_url . $uri;

        $data = [
            'json' => [
                'type' => 'program'
            ]
        ];

        return $this->patch($url, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function changeToPendingToNormalSolution(int $solution_id)
    {
        $uri = str_replace('(:num)', $solution_id, SolutionApiEnum::TYPE);
        $url = $this->hwtrek_url . $uri;

        $data = [
            'json' => [
                'type' => 'pending-to-normal-solution'
            ]
        ];

        return $this->patch($url, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function changeToPendingToProgram(int $solution_id)
    {
        $uri = str_replace('(:num)', $solution_id, SolutionApiEnum::TYPE);
        $url = $this->hwtrek_url . $uri;

        $data = [
            'json' => [
                'type' => 'pending-to-program'
            ]
        ];

        return $this->patch($url, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function approve(int $solution_id)
    {
        $uri = str_replace('(:num)', $solution_id, SolutionApiEnum::PUBLICITY);
        $url = $this->hwtrek_url . $uri;

        return $this->post($url);
    }

    /**
     * {@inheritDoc}
     */
    public function reject(int $solution_id)
    {
        $uri = str_replace('(:num)', $solution_id, SolutionApiEnum::PUBLICITY);
        $url = $this->hwtrek_url . $uri;

        return $this->delete($url);
    }

    /**
     * {@inheritDoc}
     */
    public function modifySolution(int $solution_id, array $data)
    {
        $uri = str_replace('(:num)', $solution_id, SolutionApiEnum::SOLUTION);
        $url = $this->hwtrek_url . $uri;

        $data = [
            'json' => $data
        ];

        return $this->patch($url, $data);
    }
}
