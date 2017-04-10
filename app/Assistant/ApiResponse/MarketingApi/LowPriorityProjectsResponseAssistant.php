<?php
namespace Backend\Assistant\ApiResponse\MarketingApi;

use Backend\Assistant\ApiResponse\BaseResponseAssistant;
use Backend\Model\LowPriorityObject\LowPriorityProject;
use Symfony\Component\HttpFoundation\Response;

class LowPriorityProjectsResponseAssistant extends BaseResponseAssistant
{
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public static function create(Response $response)
    {
        return new LowPriorityProjectsResponseAssistant($response);
    }

    public function projects()
    {
        $projects = $this->decode();

        if (empty($projects)) {
            return [];
        }

        $result = [];

        foreach ($projects as $project) {
            $result[] = LowPriorityProject::denormalize($project);
        }

        return $result;
    }
}
