<?php

namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Project Tag Model
 *
 * @refer    hwp/application/schema/project_tag.php
 * @table    project_tag
 * @pk        id
 * @columns slug, parent_id
 **/
class ProjectTag extends Eloquent
{

    protected $table = 'project_tag';

    public $timestamps = false;

    const CONNECTIVITY = 1;
    const PHYSICAL = 2;
    const WIRELESS = 3;
    const AUDIO = 4;
    const VIDEO = 5;
    const DISPLAY = 6;
    const POWER = 7;
    const TOUCH = 8;
    const CHIP = 9;
    const OS = 10;
    const SENSOR = 11;
    const ME = 12;
    const EE = 13;

    public function getTagIdAttribute()
    {
        return $this->attributes['id'];
    }
}
