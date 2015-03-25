<?php namespace Backend\Model\ModelInterfaces;

interface SolutionModifierInterface
{
    public function update($solution_id, $data);
    public function approve($solution_id);
    public function managerApprove($solution_id);
    public function reject($solution_id);

    public function ongoingUpdate($solution_id, $data);
    public function ongoingApprove($solution_id);
    public function ongoingManagerApprove($solution_id);
    public function ongoingReject($solution_id);
}
