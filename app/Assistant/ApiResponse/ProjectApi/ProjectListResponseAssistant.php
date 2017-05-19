<?php
namespace Backend\Assistant\ApiResponse\ProjectApi;

use Backend\Assistant\ApiResponse\BaseResponseAssistant;
use Backend\Enums\API\Response\Key\ProjectKey;
use Backend\Enums\API\Response\Key\SolutionKey;
use Backend\Model\Project\Entity\BasicProject;
use Backend\Model\Solution\Entity\BasicSolution;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class ProjectListResponseAssistant extends BaseResponseAssistant
{
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public static function create(Response $response)
    {
        return new ProjectListResponseAssistant($response);
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

        return $response[ProjectKey::KEY_TOTAL_COUNT];
    }

    /**
     * @return int
     */
    public function getNotYetEmailOUtCount()
    {
        if (!$this->response->isOk()) {
            return 0;
        }

        $response = $this->decode();

        return $response[ProjectKey::KEY_NOT_YET_EMAIL_OUT_COUNT];
    }

    /**
     * @return BasicProject[]|Collection
     */
    public function getProjectList()
    {
        $collection = Collection::make();

        if (!$this->response->isOk()) {
            return $collection;
        }

        $response = $this->decode();

        $project_list = $response[ProjectKey::KEY_PROJECT_LIST];

        foreach ($project_list as $project) {
            $collection->push(BasicProject::denormalize($project));
        }

        return $collection;
    }

    /**
     * @param int $limit
     * @return Paginator
     */
    public function getProjectListPaginate(int $limit)
    {
        $paginator = new Paginator($this->getProjectList(), $this->getTotalCount(), $limit);

        $paginator->setPath('/' . \Request::path());

        return $paginator;
    }
}
