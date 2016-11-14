<?php

namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Builder;

class CommentFollower extends Eloquent
{
    protected $table = 'comment_follower';
    protected $primaryKey = 'id';
    public static $unguarded = true;
}
