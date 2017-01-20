<?php namespace Backend\Model\ModelInterfaces;

interface SolutionModifierInterface
{
    public function updateImageGalleries($solution_id, $data);
    public function approve($solution_id);
    public function managerApprove($solution_id);
    public function toProgram($solution_id);
    public function managerToProgram($solution_id);
    public function toSolution($solution_id);
    public function managerToSolution($solution_id);
    public function reject($solution_id);
    public function onShelf($solution_id);
    public function offShelf($solution_id);
}
