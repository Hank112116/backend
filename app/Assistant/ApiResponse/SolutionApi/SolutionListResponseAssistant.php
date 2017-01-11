<?php
namespace Backend\Assistant\SolutionApi;

use Backend\Assistant\ApiResponse\BaseResponseAssistant;
use Backend\Enums\API\Response\Key\SolutionKey;
use Backend\Model\Solution\Entity\BasicSolution;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class SolutionListResponseAssistant extends BaseResponseAssistant
{
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public static function create(Response $response)
    {
        return new SolutionListResponseAssistant($response);
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        if (!$this->response->isOk()) {
            return 0;
        }

        $response = $this->decode();

        return $response[SolutionKey::KEY_TOTAL_COUNT];
    }

    /**
     * @return bool
     */
    public function hasWaitPublishSolution()
    {
        if (!$this->response->isOk()) {
            return false;
        }

        $response = $this->decode();

        return $response[SolutionKey::KEY_HAS_WAIT_PUBLISH];
    }

    /**
     * @return Collection
     */
    public function getSolutionList()
    {
        $collection = Collection::make();

        if (!$this->response->isOk()) {
            return $collection;
        }

        $response = $this->decode();

        $solution_list = $response[SolutionKey::KEY_SOLUTION_LIST];

        foreach ($solution_list as $solution) {
            $collection->push(BasicSolution::denormalize($solution));
        }

        return $collection;
    }

    /**
     * @param int $limit
     * @return Paginator
     */
    public function getSolutionListPaginate(int $limit)
    {
        $paginator = new Paginator($this->getSolutionList(), $this->getTotalCount(), $limit);

        $paginator->setPath('/' . \Request::path());

        return $paginator;
    }
}
