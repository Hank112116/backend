<?php namespace Backend\Repo\RepoInterfaces;

interface RoleInterface
{
    public function all();

    public function find($id);

    public function allNames();

    public function create($data);

    public function update($id, $data);

    public function isRoleHasAdminer($role);

    public function deleteRole($role);
}
