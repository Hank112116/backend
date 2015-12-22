<?php namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserComment extends Eloquent
{
    protected $table = 'user_comment';
    protected $primaryKey = 'comment_id';
    public static $unguarded = true;

    public function comment()
    {
        return $this->hasOne(NewComment::class, 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
}
