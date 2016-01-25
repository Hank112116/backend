<?php namespace Backend\Repo\RepoInterfaces;

interface EventApplicationInterface
{
    /**
     * @return mixed
     */
    public function all();
    public function findByEventId($event_id);
    public function getEvents();
    public function getDefaultEvent();
    public function byCollectionPage($collection, $page = 1, $per_page = 50);
    public function updateEventNote($id, $note);
    public function approveEventUser($id);
}
