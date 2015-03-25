<?php namespace Backend\Model\Plain;

class ProjectProgress
{
    const PROGRESS_BRINSTROMING = 1;
    const PROGRESS_POC = 2;
    const PROGRESS_PROTOTYPE = 3;
    const PROGRESS_INDUSTRIAL_DESIGN = 4;
    const PROGRESS_IMPROVING = 5;
    const PROGRESS_MANUFACTURABILITY = 6;

    private static $progresses = [
        self::PROGRESS_BRINSTROMING      => 'Brainstorming an idea',
        self::PROGRESS_POC               => 'Proof of concept',
        self::PROGRESS_PROTOTYPE         => 'Working prototype',
        self::PROGRESS_INDUSTRIAL_DESIGN => 'Enhancing industrial design',
        self::PROGRESS_IMPROVING         => 'Improving electronic board',
        self::PROGRESS_MANUFACTURABILITY => 'Design for manufacturability',
    ];

    private $solution_looking_progress_ids = null;
    private $solution_looking_progress_texts = null;

    public function textProgress($progress)
    {
        return array_key_exists($progress, self::$progresses) ?
            self::$progresses[ $progress ] : 'N/A';
    }

    public function progresses()
    {
        return self::$progresses;
    }

    public function parseSolutionLookingProgress($progress_implode, $progress_other)
    {
        $this->parseProgress($progress_implode);

        if (mb_strlen($progress_other) == 0) {
            return;
        }

        $this->solution_looking_progress_texts[] = $progress_other;
    }

    private function parseProgress($progress_implode)
    {
        $this->solution_looking_progress_ids = [];
        $this->solution_looking_progress_texts = [];

        if (mb_strlen($progress_implode) == 0) {
            return [];
        }

        foreach (explode(',', $progress_implode) as $progress) {
            if (array_key_exists($progress, self::$progresses)) {
                $this->solution_looking_progress_ids[] = $progress;
                $this->solution_looking_progress_texts[] = self::$progresses[ $progress ];
            }
        }
    }

    public function solutionLookingProgressTexts()
    {
        return $this->solution_looking_progress_texts;
    }

    public function solutionContains($progress_id)
    {
        return in_array($progress_id, $this->solution_looking_progress_ids);
    }
}
