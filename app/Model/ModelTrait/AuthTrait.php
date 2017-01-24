<?php namespace Backend\Model\ModelTrait;

use Backend\Model\Eloquent\Adminer;

trait AuthTrait
{
    protected $cert;

    /**
     * @return bool
     */
    public function isRestricted()
    {
        if (!isset($this->cert)) {
            return false;
        }

        if (!auth()->check()) {
            return true;
        }

        /* @var Adminer $admin */
        $admin = auth()->user();

        return $admin->isRestricted($this->cert);
    }

    /**
     * @return bool
     */
    public function isLimitedEditor()
    {
        if (!isset($this->cert)) {
            return false;
        }

        /* @var Adminer $admin */
        $admin = auth()->user();

        return $admin->isLimitedEditor($this->cert);
    }

    /**
     * @return bool
     */
    public function isSuperAdmin()
    {
        if (!auth()->check()) {
            return false;
        }

        /* @var Adminer $admin */
        $admin = auth()->user();

        return $admin->isSuperAdmin();
    }

    /**
     * @return Adminer
     */
    public function authUser()
    {
        return auth()->user();
    }
}
