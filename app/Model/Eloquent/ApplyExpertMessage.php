<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ApplyExpertMessage extends Eloquent
{
    protected $table = 'apply_expert_message';
    protected $primaryKey = 'id';
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
