<?php namespace Backend\Repo\RepoInterfaces;

use Backend\Model\Eloquent\HubSchedule;
use Backend\Model\Eloquent\Project;

interface HubInterface
{
    public function findQuestionnaire($id);

    /**
     * @param $id
     * @return HubSchedule
     */
    public function findSchedule($id);

    public function allQuestionnaires();
    public function allSchedules();

    public function dummySchedule();

    public function updateScheduleManagers(\Backend\Model\Eloquent\HubSchedule $schedule, $data);
    public function approveSchedule(Project $schedule);
}
