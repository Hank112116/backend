<?php

namespace Backend\Api\Lara\SolutionApi;

use Backend\Api\ApiInterfaces\SolutionApi\SolutionApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\URI\API\HWTrek\SolutionApiEnum;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    /**
     * {@inheritDoc}
     */
    public function onShelf(int $solution_id)
    {
        $uri = str_replace('(:num)', $solution_id, SolutionApiEnum::VISIBILITY);
        $url = $this->hwtrek_url . $uri;

        $data = [
            'json' => [
                'visibility' => 'public'
            ]
        ];

        return $this->patch($url, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function offShelf(int $solution_id)
    {
        $uri = str_replace('(:num)', $solution_id, SolutionApiEnum::VISIBILITY);
        $url = $this->hwtrek_url . $uri;

        $data = [
            'json' => [
                'visibility' => 'private'
            ]
        ];

        return $this->patch($url, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function uploadPicture(int $solution_id, UploadedFile $file)
    {
        $uri = str_replace('(:num)', $solution_id, SolutionApiEnum::PICTURE);
        $url = $this->hwtrek_url . $uri;

        $upload_dir = '/tmp/';

        $file->move($upload_dir, $file->getClientOriginalName());
        $file_path = $upload_dir . $file->getClientOriginalName();

        $options = [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => fopen($file_path, "r"),
                ]
            ]
        ];

        $response = $this->post($url, $options);

        unlink($file_path);

        return $response;
    }
}
