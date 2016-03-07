<?php namespace Backend\Repo\RepoInterfaces;

use Backend\Model\Eloquent\EventApplication;
use Illuminate\Database\Eloquent\Collection;

interface EventQuestionnaireInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @param $user_id
     * @return Collection EventQuestionnaire[]
     */
    public function findByUserId($user_id);

    /**
     * @param $event_id
     * @return mixed
     */
    public function findByEventId($event_id);

    /**
     * @param EventApplication $user
     * @return mixed
     */
    public function findByApproveUser(EventApplication $user);

    /**
     * @param $event_id
     * @return mixed
     */
    public function getQuestionnaireColumn($event_id);

    /**
     * @param $event_id
     * @return mixed
     */
    public function getView($event_id);
}
