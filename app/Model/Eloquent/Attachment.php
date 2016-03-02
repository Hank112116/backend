<?php

namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Attachment extends Eloquent
{
    protected $table = 'attachment';
    protected $primaryKey = 'id';
    public static $unguarded = true;
}
