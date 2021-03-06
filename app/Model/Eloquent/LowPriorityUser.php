<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class LowPriorityUser extends Eloquent
{
    protected $table = 'low_priority_user';
    protected $primaryKey = 'user_id';
    public $timestamps = false;
    public static $unguarded = true;

    public function getKeyName()
    {
        return $this->primaryKey;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
