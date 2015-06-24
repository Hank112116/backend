<?php namespace Backend\Repo\Lara;
use Backend\Repo\RepoInterfaces\ProjectMailExpertInterface;
use Backend\Model\Eloquent\ProjectMailExpert;

class ProjectMailExpertRepo implements ProjectMailExpertInterface
{
    public function __construct(ProjectMailExpert $projectMailExpert)
    {
        $this->projectMailExpert =  $projectMailExpert;
    }
    public function insertItem($data)
    {
        $model = new ProjectMailExpert();
        $model->project_id = $data["project_id"];
        $model->expert_id  = $data["expert_id"];
        $model->admin_id   = $data["admin_id"];
        $model->date_send  = $data["date_send"];
        return $model->save();
    }
}
