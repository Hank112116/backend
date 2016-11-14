<?php namespace Backend\Model\Eloquent;

use Sinergi\BrowserDetector\Browser;
use Illuminate\Database\Eloquent\Model;

class LogAccessHello extends Model
{

    protected $table = 'log_access_hello';
    protected $primaryKey = 'log_id';
    public $timestamps = false; // not use created_at, updated_at

    protected $browser = null;

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function getBrowserAttribute()
    {
        if (is_null($this->browser)) {
            $this->browser = new Browser($this->user_agent);
        }

        return $this->browser;
    }
}
