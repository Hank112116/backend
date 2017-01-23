<?php

namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Adminer extends Eloquent implements AuthenticatableContract
{
    use Authenticatable;
    use SoftDeletes;

    protected $table = 'adminers';
    protected $hidden = ['password'];
    protected $dates = ['deleted_at'];

    public function id()
    {
        return $this->id;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'hwtrek_member', 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isShowLink($cert)
    {
        return str_contains(session('cert'), $cert);
    }

    public function isRestricted($route_prefix)
    {
        // check config/cert.php for restricted certifications
        return str_contains($this->role->cert, "{$route_prefix}_restricted");
    }

    public function isLimitedEditor($route_prefix)
    {
        // check config/cert.php for edit restricted certifications
        return str_contains($this->role->cert, "{$route_prefix}_edit_restricted");
    }

    public function isAdmin()
    {
        return $this->role->isAdmin();
    }

    public function isManager()
    {
        return $this->role->isManager();
    }

    public function isManagerHead()
    {
        return $this->role->isManagerHead();
    }

    public function isFrontendPM()
    {
        return $this->role->isFrontendPM();
    }

    public function isBackendPM()
    {
        return $this->role->isBackendPM();
    }

    public function isEditor()
    {
        return $this->role->isEditor();
    }

    public function isSuperAdmin()
    {
        return $this->isAdmin() or $this->isBackendPM();
    }

    public function hasHWTrekMember()
    {
        return !is_null($this->hwtrek_member);
    }

    public function handleDuplicateLoginSession()
    {
        // if has duplicate login, user_id set 0
        \DB::table('admin_sessions')
            ->where('user_id', $this->id())
            ->where('id', '<>', session()->getId())
            ->update(['user_id' => 0]);
    }
}
