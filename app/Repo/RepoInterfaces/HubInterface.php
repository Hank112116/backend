<?php namespace Backend\Repo\RepoInterfaces;

interface HubInterface
{
    public function findQuestionnaire($id);
    public function findSchedule($id);

    public function allQuestionnaires();
    public function allSchedules();

    public function dummySchedule();

    public function updateScheduleManagers(\Backend\Model\Eloquent\HubSchedule $schedule, $data);
    public function approveSchedule(\Backend\Model\Eloquent\HubSchedule $schedule);
}
