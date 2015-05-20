<?php namespace Backend\Repo\RepoInterfaces;

interface AdminerInterface
{
    public function all();
    public function allDeleted();

    public function find($id);

    public function validCreate($data);

    public function error();

    public function create($data);

    public function validUpdate($id, $data);

    public function update($id, $data);

    public function deleteAdminer($adminer);

    public function toOutputArray($adminers);
}
