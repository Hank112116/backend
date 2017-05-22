<?php
namespace Backend\Assistant\ApiResponse\ProjectApi;

use Backend\Assistant\ApiResponse\BaseResponseAssistant;
use Backend\Model\Project\Entity\DetailProject;
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
     * @return DetailProject|null
     */
    public function getDetailProject()
    {
        if (!$this->response->isOk()) {
            return null;
        }

        $response = $this->decode();

        return DetailProject::denormalize($response);
    }
}
