<?php
namespace Backend\Assistant\ApiResponse\ProjectApi;

use Backend\Assistant\ApiResponse\BaseResponseAssistant;
use Backend\Model\Project\Entity\BasicProject;
use Symfony\Component\HttpFoundation\Response;

class ProjectResponseAssistant extends BaseResponseAssistant
{
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public static function create(Response $response)
    {
        return new ProjectResponseAssistant($response);
    }

    /**
     * @return BasicProject|null
     */
    public function getBasicProject()
    {
        if (!$this->response->isOk()) {
            return null;
        }

        $response = $this->decode();

        return BasicProject::denormalize($response);
    }
}
