<?php namespace Backend\Repo\Lara;

use Carbon;
use Backend\Model\Eloquent\NewComment;
use Illuminate\Database\Eloquent\Collection;
use Backend\Repo\RepoInterfaces\NewCommentInterface;

class NewCommentRepo implements NewCommentInterface
{
    public function __construct()
    {
    }
}
