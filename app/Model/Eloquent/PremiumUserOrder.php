<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class PremiumUserOrder extends Eloquent
{
    protected $table = 'premium_user_order';
    protected $primaryKey = 'id';
    protected $dates = ['created_at', 'expired_at', 'suspended_at'];
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
