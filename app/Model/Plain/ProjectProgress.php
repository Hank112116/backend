<?php namespace Backend\Model\Plain;

class ProjectProgress
{
    private static $progresses = [
        'tbd'               => 'TBD',
        'brainstorming'     => 'Idea / Brainstorming',
        'poc'               => 'Proof of concept',
        'prototype'         => 'Working prototype',
        'industrial-design' => 'Industrial design',
        'improving'         => 'Electronic board design',
        'manufacturability' => 'Design for manufacturability'
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
