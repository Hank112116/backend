<?php

namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @refer   : hwp/application/schema/home_page_demo.php
 * @table   : home_page_demo
 * @pk      : id
 * @columns : block_type, block_data, order
 * @useless : block_display
 **/

class Feature extends Eloquent
{
    const TYPE_PROJECT  = 'project';
    const TYPE_SOLUTION = 'solution';
    const TYPE_EXPERT   = 'expert';
    const TYPE_PROGRAM  = 'program';

    protected $table = 'home_page_demo';
    public $timestamps = false;
    public static $unguarded = true;

    private static $types = [
        self::TYPE_PROJECT,
        self::TYPE_SOLUTION,
        self::TYPE_EXPERT
    ];

    public function getEntityId()
    {
        switch ($this->block_type) {
            case self::TYPE_PROJECT:
                return "project_{$this->block_data}";
            case self::TYPE_SOLUTION:
                return "solution_{$this->block_data}";
            case self::TYPE_EXPERT:
                return "expert_{$this->block_data}";
            case self::TYPE_PROGRAM:
                return "program_{$this->block_data}";
        }
    }

    public function types()
    {
        return self::$types;
    }

    public function isExpert()
    {
        return $this->block_type == self::TYPE_EXPERT;
    }

    public function isProject()
    {
        return $this->block_type == self::TYPE_PROJECT;
    }

    public function isSolution()
    {
        return $this->block_type == self::TYPE_SOLUTION;
    }
    public function isProgram()
    {
        return $this->block_type == self::TYPE_PROGRAM;
    }
}
