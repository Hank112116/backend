<?php

namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @refer   : hwp/application/schema/reference_project.php
 * @table   : reference_project
 * @columns : project_id, url_project_title, order
 **/

class ReferenceProject extends Eloquent
{

    protected $table = 'reference_project';

    protected $primaryKey = null;
    public $incrementing = false;

    public $timestamps = false;
    public static $unguarded = true;

    public function project()
    {
        return $this->hasOne(Project::class, 'project_id', 'project_id');
    }
}
