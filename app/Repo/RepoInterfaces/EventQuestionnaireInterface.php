<?php namespace Backend\Repo\RepoInterfaces;

interface EventQuestionnaireInterface
{
    public function find($id);
    public function findByUserId($user_id);
    public function findByEventId($event_id);
    public function getQuestionnaireColumn($event_id);
    public function getView($event_id);
}
