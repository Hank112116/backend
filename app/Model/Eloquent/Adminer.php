<?php

namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

use Session;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Adminer extends Eloquent implements AuthenticatableContract
{

    use Authenticatable;

    protected $table = 'adminers';
    protected $hidden = ['password'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isShowLink($cert)
    {
        return str_contains(Session::get('cert'), $cert);
    }

    public function isRestricted($route_prefix)
    {
        // check config/cert.php for restricted certifications
        return str_contains($this->role->cert, "{$route_prefix}_restricted");
    }

    public function isAdmin()
    {
        return $this->role->isAdmin();
    }

    public function isManager()
    {
        return $this->role->isManager();
    }
}
