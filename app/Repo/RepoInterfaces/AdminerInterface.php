<?php namespace Backend\Repo\RepoInterfaces;

use Backend\Model\Eloquent\Adminer;
use Illuminate\Database\Eloquent\Collection;

interface AdminerInterface
{
    /**
     * @return Collection|Adminer[]
     */
    public function all();

    public function allDeleted();

    /**
     * @param $hwtrek_member
     * @return Adminer
     */
    public function find($id);

    /**
     * @param $email
     * @return Adminer
     */
    public function findByEmail($email);

    /**
     * @param $hwtrek_member
     * @return Adminer
     */
    public function findHWTrekMember($hwtrek_member);

    /**
     * @param $hwtrek_member
     * @return Adminer
     */
    public function findWithTrashed($id);

    public function validCreate($data);

    public function error();

    public function create($data);

    public function validUpdate($id, $data);

    public function update($id, $data);

    public function deleteAdminer($adminer);

    public function toOutputArray($adminers);

    /**
     * @return Collection|Adminer[]
     */
    public function findFrontManager();

    /**
     * @return Collection|Adminer[]
     */
    public function findBackManager();

    /**
     * @param array $ids
     * @return Collection|Adminer[]
     */
    public function findAssignedProjectPM(array $ids);
}
