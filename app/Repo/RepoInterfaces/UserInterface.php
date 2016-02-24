<?php namespace Backend\Repo\RepoInterfaces;

use Illuminate\Support\Collection;

interface UserInterface
{
    public function find($id);
    public function findExpert($id);
    public function all();

    public function byPage($page, $per_page);
    public function byCollectionPage($collection, $page, $per_page);
    public function experts();
    public function creators();
    public function toBeExpertMembers();
    public function toBeExpertMemberIds();

    public function byId($user_id);
    public function byName($name);
    public function byMail($email);
    public function byCompany($company);
    public function byDateRange($dstart, $dend);

    public function validUpdate($id, $data);
    public function errors();

    public function update($id, $data);

    public function dummy();
    public function filterExpertsWithToBeExperts(Collection $users);
    public function filterCreatorWithoutToBeExperts(Collection $users);
    public function filterExperts(Collection $users);
    public function filterCreator(Collection $users);
    public function filterPM(Collection $users);

    public function getCommentCountsByDate($dstart, $dend);
    public function getCommentCountsByDateById($dstart, $dend, $id);
    public function getCommentCountsByDateByName($dstart, $dend, $name);

    public function toOutputArray($users);

    public function changeHWTrekPM($user_id, $is_hwtrek_pm);

    //    public function valid();
    //    public function error();
    //    public function update();
}
