<?php

// id, name, cert, created_at, updated_at
namespace Backend\Model\Eloquent;
use Config;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Role extends Eloquent
{

    protected $table = 'admin_roles';

    private static $certs_all = [];
    private $certs_arr = [];

    public function adminers()
    {
        return $this->hasMany(Adminer::class);
    }

    public function isAdmin()
    {
        return $this->name == 'admin';
    }

    public function isManagerHead()
    {
        return $this->name == 'manager head';
    }

    public function isManager()
    {
        return $this->name == 'manager';
    }

    public function getCertsArr()
    {
        if (!$this->certs) {
            $this->certs_arr = explode(',', $this->cert);
        }

        return $this->certs_arr;
    }

    public function getCertName($code)
    {
        if (!self::$certs_all) {
            self::$certs_all = [];
            foreach (Config::get('cert.all') as $cert_arr) {
                self::$certs_all = array_merge(self::$certs_all, $cert_arr);
            }
        }

        return self::$certs_all[$code];
    }

    public function hasCert($cert)
    {
        if (!$this->certs) {
            $this->certs_arr = explode(',', $this->cert);
        }

        return in_array($cert, $this->certs_arr);
    }
}
