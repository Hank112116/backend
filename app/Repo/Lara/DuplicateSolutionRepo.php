<?php namespace Backend\Repo\Lara;

use Backend\Model\ModelInterfaces\SolutionModifierInterface;
use ImageUp;
use Backend\Model\Eloquent\Solution;
use Backend\Model\Eloquent\DuplicateSolution;
use Backend\Repo\RepoInterfaces\DuplicateSolutionInterface;
use Backend\Repo\RepoInterfaces\ExpertiseInterface;

class DuplicateSolutionRepo implements DuplicateSolutionInterface
{
    private $wait_approve;

    public function __construct(
        Solution $solution,
        DuplicateSolution $duplicate,
        ExpertiseInterface $expertise,
        SolutionModifierInterface $solution_modifier,
        ImageUp $image_uploader
    ) {
        $this->solution = $solution;
        $this->duplicate = $duplicate;

        $this->expertise = $expertise;
        $this->solution_modifier = $solution_modifier;

        $this->image_uploader = $image_uploader;
    }

    public function find($id)
    {
        return $this->duplicate->find($id);
    }

    public function waitApproveSolutionIds()
    {
        if (!isset($this->wait_approve)) {
            $this->wait_approve = $this->duplicate
                ->queryWaitApproved()
                ->lists('solution_id');
        }

        return $this->wait_approve;
    }

    public function waitApproveSolutions()
    {
        $solution_ids = $this->waitApproveSolutionIds() ?: [0];

        return $this->duplicate
            ->whereIn('solution_id', $solution_ids)
            ->orderBy('solution_id', 'desc')
            ->get();
    }

    public function update($solution_id, $data)
    {
        $this->solution_modifier->ongoingUpdate($solution_id, $data);
    }

    public function approve($solution_id, $is_manager)
    {
        if ($is_manager) {
            $this->solution_modifier->ongoingManagerApprove($solution_id);
        } else {
            $this->solution_modifier->ongoingApprove($solution_id);
        }
    }

    public function reject($solution_id)
    {
        $this->solution_modifier->ongoingReject($solution_id);
    }
}
