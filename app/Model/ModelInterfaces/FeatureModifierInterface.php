<?php namespace Backend\Model\ModelInterfaces;

interface FeatureModifierInterface
{
    public function updateType($data);

    public function solutionFeatureToProgram($solution_id);

    public function programFeatureToNormalSolution($solution_id);
}
