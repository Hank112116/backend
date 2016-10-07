<?php namespace Backend\Repo\RepoTrait;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Collection;

use Illuminate\Pagination\LengthAwarePaginator as Paginator;

trait PaginateTrait
{
    protected function setPaginateTotal($total)
    {
        $this->total = $total;
    }

    protected function total(Eloquent $model)
    {
        return isset($this->total) ? $this->total : $model->count();
    }

    /*
     * @param Eloquant $model
     * @param int $page
     * @param int $limit
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function modelBuilder(Eloquent $model, $page, $limit)
    {
        return $model->orderBy($model->getPrimaryKey(), 'desc')
            ->skip($limit * ($page -1))
            ->take($limit);
    }

    protected function getContainer($model, $page, $limit, Collection $collection)
    {
        $result             = new \StdClass();
        $result->page       = $page;
        $result->limit      = $limit;
        $result->items      = $collection->all();
        $result->totalItems = $this->total($model);

        return $result;
    }

    protected function getPaginateContainer($model, $page, $limit, $users)
    {
        $container = $this->getContainer($model, $page, $limit, $users);

        $paginator = new Paginator($container->items, $container->totalItems, $limit);
        $paginator->setPath('/' . \Request::path());

        return $paginator;
    }

    protected function getPaginateFromCollection($collection, $currentPage, $perPage)
    {
        $pagedData = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginator = new Paginator($pagedData, $collection->count(), $perPage);
        $paginator->setPath('/' . \Request::path());

        return $paginator;
    }

    protected function getSearchPaginateContainer($total, $perPage, Collection $collection)
    {
        $paginator = new Paginator($collection, $total, $perPage);
        $paginator->setPath('/' . \Request::path());
        return $paginator;
    }
}
