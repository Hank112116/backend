<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class InternalUserMemo extends Eloquent
{
    protected $table = 'internal_user_memo';
    protected $primaryKey = 'id';
    public static $unguarded = true;

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }
}
