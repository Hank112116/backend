<?php
namespace Backend\Assistant\ApiResponse\MarketingApi;

use Backend\Assistant\ApiResponse\BaseResponseAssistant;
use Backend\Model\LowPriorityObject\LowPrioritySolution;
use Symfony\Component\HttpFoundation\Response;

class LowPrioritySolutionsResponseAssistant extends BaseResponseAssistant
{
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public static function create(Response $response)
    {
        return new LowPrioritySolutionsResponseAssistant($response);
    }

    public function solutions()
    {
        $solutions = $this->decode();

        if (empty($solutions)) {
            return [];
        }

        $result = [];

        foreach ($solutions as $solution) {
            $result[] = LowPrioritySolution::denormalize($solution);
        }

        return $result;
    }
}
