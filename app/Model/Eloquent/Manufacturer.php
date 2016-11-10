<?php

namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @refer   : hwp/application/schema/manufacturers.php
 * @table   : manufacturers
 * @pk      : id
 * @columns : name, description, img_url, web_url
 **/

class Manufacturer extends Eloquent
{

    protected $table = 'manufacturers';
    public $timestamps = false;
    public static $unguarded = true;

    public function getImage()
    {
        return $this->img_url ?: $this->getDefaultImage();
    }

    public function getDefaultImage()
    {
        return config('s3.default_project');
    }
}
