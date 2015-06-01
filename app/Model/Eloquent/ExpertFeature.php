<?php namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ExpertFeature extends Eloquent {

	//
    protected $table = 'home_page_expert';
    public $timestamps = false;
    public static $unguarded = true;
}