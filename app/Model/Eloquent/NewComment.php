<?php

namespace Backend\Model\Eloquent;

use Config;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Builder;

class NewComment extends Eloquent
{

    protected $table = 'comment';
    protected $primaryKey = 'id';
    public static $unguarded = true;
}
