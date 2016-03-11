<?php namespace Backend\Repo\RepoInterfaces;

use Illuminate\Database\Eloquent\Collection;

interface EventApplicationInterface
{
    /**
     * @return Collection EventApplication[]
     */
    public function all();

    /**
     * @param $event_id
     * @return Collection EventApplication[]
     */
    public function findByEventId($event_id);

    /**
     * @param $user_id
     * @return Collection EventApplication[]
     */
    public function findByUserId($user_id);

    /**
     * @param $event_id
     * @return Collection EventApplication[]
     */
    public function findApproveEventUsers($event_id);

    /**
     * @return array
     */
    public function getEvents();

    /**
     * @return mixed
     */
    public function getDefaultEvent();

    /**
     * @param     $collection
     * @param int $page
     * @param int $per_page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function byCollectionPage($collection, $page = 1, $per_page = 50);

    /**
     * @param $id
     * @param $note
     * @return boolean
     */
    public function updateEventNote($id, $note);

    /**
     * @param $user_id
     * @return boolean
     */
    public function approveEventUser($user_id);
}
