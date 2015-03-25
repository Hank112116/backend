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

    protected $table = 'home_page_demo';
    public $timestamps = false;
    public static $unguarded = true;

    private static $types = ['project', 'expert', 'solution'];

    public function getEntityId()
    {
        switch ($this->block_type) {
            case 'project' :
                return "project_{$this->block_data}";
            case 'solution':
                return "solution_{$this->block_data}";
            case 'expert'  :
                return "expert_{$this->block_data}";
        }
    }

    public function types()
    {
        return self::$types;
    }

    public function isExpert()
    {
        return $this->block_type == 'expert';
    }

    public function isProject()
    {
        return $this->block_type == 'project';
    }

    public function isSolution()
    {
        return $this->block_type == 'solution';
    }
}
