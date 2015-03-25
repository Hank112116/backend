<?php namespace Backend\Model\ModelInterfaces;

interface ProjectModifierInterface
{
    public function updateProject($project_id, $data);
    public function toDraftProject($project_id);
    public function toSubmittedPrivateProject($project_id);
    public function toSubmittedPublicProject($project_id);
}
