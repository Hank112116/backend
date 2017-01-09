<?php namespace Backend\Model;

use Backend\Model\Eloquent\Feature;
use Backend\Model\ModelInterfaces\FeatureModifierInterface;

class FeatureModifier implements FeatureModifierInterface
{
    private $feature;

    public function __construct(Feature $feature)
    {
        $this->feature = $feature;
    }

    public function updateType($data)
    {
        $feature = $this->feature
                        ->where('block_data', $data['block_data'])
                        ->where('block_type', $data['block_type'])
                        ->first();
        if ($feature) {
            $feature->block_type = $data['to_block_type'];
            $feature->save();
        }
    }

    public function solutionFeatureToProgram($solution_id)
    {
        $feature = $this->feature
            ->where('block_data', $solution_id)
            ->where('block_type', Feature::TYPE_SOLUTION)
            ->first();
        if ($feature) {
            $feature->block_type = Feature::TYPE_PROGRAM;
            $feature->save();
        }
    }

    public function programFeatureToNormalSolution($solution_id)
    {
        $feature = $this->feature
            ->where('block_data', $solution_id)
            ->where('block_type', Feature::TYPE_PROGRAM)
            ->first();
        if ($feature) {
            $feature->block_type = Feature::TYPE_SOLUTION;
            $feature->save();
        }
    }
}
