<?php namespace Backend\Model\ModelInterfaces;

interface ProjectModifierInterface
{
    public function updateProject($project_id, $data);
    public function toDraftProject($project_id);
    public function toSubmittedPrivateProject($project_id);
    public function toSubmittedPublicProject($project_id);
    public function updateProjectMemo($project_id, $data);
    public function updateProjectTeam($project_id, $data);
    public function updateProjectManager($project_id, $data);
    public function projectManagerValidate($pms);
}
