<?php
namespace Backend\Model\Feature;

class FeatureStatistics
{
    private $expert_count         = 0;
    private $premium_expert_count = 0;
    private $project_count        = 0;
    private $solution_count       = 0;
    private $program_count        = 0;

    public function countExpert()
    {
        $this->expert_count ++;
    }

    public function getExpertCount()
    {
        return $this->expert_count;
    }

    public function countPremiumExpert()
    {
        $this->premium_expert_count ++;
    }

    public function getPremiumExpertCount()
    {
        return $this->premium_expert_count;
    }

    public function countProject()
    {
        $this->project_count ++;
    }

    public function getProjectCount()
    {
        return $this->project_count;
    }

    public function countSolution()
    {
        $this->solution_count ++;
    }

    public function getSolutionCount()
    {
        return $this->solution_count;
    }

    public function countProgram()
    {
        $this->program_count ++;
    }

    public function getProgramCount()
    {
        return $this->program_count;
    }

    public function statisticsData()
    {
        return [
            0 => [
                'text'  => 'Expert',
                'count' => $this->getExpertCount()
            ],
            1 => [
                'text'  => 'Premium Expert',
                'count' => $this->getPremiumExpertCount()
            ],
            2 => [
                'text'  => 'Project',
                'count' => $this->getProjectCount()
            ],
            3 => [
                'text'  => 'Solution',
                'count' => $this->getSolutionCount()
            ],
            4 => [
                'text'  => 'Program',
                'count' => $this->getProgramCount()
            ],
        ];
    }
}
