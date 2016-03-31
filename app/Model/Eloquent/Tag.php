<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Tag extends Eloquent
{
    protected $table = 'tag';
    protected $primaryKey = 'id';
    public static $unguarded = true;
}
