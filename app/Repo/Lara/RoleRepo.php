<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\Role;
use Backend\Repo\RepoInterfaces\RoleInterface;

class RoleRepo implements RoleInterface
{
    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function all()
    {
        return $this->role->all();
    }

    public function find($id)
    {
        return $this->role->find($id);
    }

    public function allNames()
    {
        return $this->role->lists('name', 'id');
    }

    public function create($input)
    {
        $role       = new Role();
        $role->name = $input[ 'name' ];
        $role->cert = implode(',', $input[ 'cert' ]);
        $role->save();

        $render = [
            'success' => true,
            'id'      => $role->id
        ];

        return $render;
    }

    public function update($id, $input)
    {
        $role       = $this->role->find($id);
        $role->name = $input[ 'name' ];
        $role->cert = implode(',', $input[ 'cert' ]);
        $role->save();
    }

    public function isRoleHasAdminer($role)
    {
        if ($this->role->adminers) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteRole($role)
    {
        $role->delete();
    }
}
