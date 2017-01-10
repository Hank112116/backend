<?php
namespace Backend\Assistant\SolutionApi;

use Backend\Assistant\ApiResponse\BaseResponseAssistant;
use Backend\Model\Solution\Entity\DetailSolution;
use Symfony\Component\HttpFoundation\Response;

class SolutionResponseAssistant extends BaseResponseAssistant
{
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public static function create(Response $response)
    {
        return new SolutionResponseAssistant($response);
    }

    /**
     * @return DetailSolution|null
     */
    public function getSolution()
    {
        if (!$this->response->isOk()) {
            return null;
        }

        $response = $this->decode();

        return new DetailSolution($response);
    }
}
