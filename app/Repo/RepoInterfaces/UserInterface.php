<?php namespace Backend\Repo\RepoInterfaces;

use Illuminate\Support\Collection;

interface UserInterface
{
    public function find($id);
    public function findExpert($id);
    public function all();

    public function byPage($page, $per_page);
    public function experts();
    public function creators();
    public function toBeExpertMembers();
    public function toBeExpertMemberIds();

    public function byId($user_id);
    public function byName($name);
    public function byMail($email);
    public function byCompany($company);
    public function byDateRange($date_range);

    public function validUpdate($id, $data);
    public function errors();

    public function update($id, $data);

    public function dummy();
    public function filterExperts(Collection $users);

    public function toOutputArray($users);

    //    public function valid();
    //    public function error();
    //    public function update();
}
