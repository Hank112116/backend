<?php

namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Builder;

class CommentReply extends Eloquent
{
    protected $table = 'comment_reply';
    protected $primaryKey = 'id';
    public static $unguarded = true;
}
