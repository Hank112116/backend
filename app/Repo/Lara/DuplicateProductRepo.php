<?php namespace Backend\Repo\Lara;

use Carbon;
use Backend\Model\Eloquent\Project;
use Backend\Model\Eloquent\DuplicateProject;
use Backend\Repo\RepoInterfaces\DuplicateProductInterface;
use Backend\Repo\RepoInterfaces\DuplicatePerkInterface;
use Backend\Model\ProjectModifier;

class DuplicateProductRepo implements DuplicateProductInterface
{
    private $wait_approve;

    public function __construct(
        Project $project,
        DuplicateProject $duplicate,
        DuplicatePerkInterface $duplicate_perk_repo,
        ProjectModifier $project_modifier
    ) {
        $this->project = $project;
        $this->duplicate = $duplicate;

        $this->duplicate_perk_repo = $duplicate_perk_repo;
        $this->project_modifier = $project_modifier;
    }

    public function find($id)
    {
        return $this->duplicate->find($id);
    }

    public function waitApproveProjectIds()
    {
        if (!isset($this->wait_approve)) {
            $this->wait_approve = $this->duplicate
                ->queryApprovePending()
                ->where('end_date', '>', Carbon::today()->addDay())
                ->lists('project_id');
        }

        return $this->wait_approve;
    }

    public function approve($project_id, $approve)
    {
        if ($approve) {
            $this->project_modifier->approveDuplicateProduct($project_id);
            $this->duplicate_perk_repo->coverPerks($project_id);
        } else {
            $this->project_modifier->rejectDuplicateProduct($project_id);
        }
    }

    public function update($project_id, $data)
    {
        $this->project_modifier->updateDuplicateProduct($project_id, $data);
        $this->duplicate_perk_repo->updateDuplicateProjectPerks($project_id, $data['perks']);
    }
}
