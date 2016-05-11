<?php namespace Backend\Repo\RepoInterfaces;

use Backend\Model\Eloquent\User;
use Illuminate\Database\Eloquent\Collection;

interface UserInterface
{
    /**
     * @param $id
     * @return User
     */
    public function find($id);

    /**
     * @param $id
     * @return User
     */
    public function findExpert($id);

    /**
     * @param $id
     * @return User
     */
    public function findWithDetail($id);

    /**
     * @return Collection User[]
     */
    public function all();

    /**
     * @param int $page
     * @param int $per_page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function byPage($page, $per_page);

    /**
     * @param $collection
     * @param $page
     * @param $per_page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function byCollectionPage($collection, $page, $per_page);

    /**
     * @param int $page
     * @param int $limit
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function experts($page, $per_page);

    /**
     * @param int $page
     * @param int $limit
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function creators($page, $per_page);

    /**
     * @return false|Collection|User[]
     */
    public function toBeExpertMembers();

    /**
     * @return Collection[]
     */
    public function toBeExpertMemberIds();

    /**
     * @param $user_id
     * @return Collection|User[]
     */
    public function byId($user_id);

    /**
     * @param $name
     * @return Collection|User[]
     */
    public function byName($name);

    /**
     * @param $email
     * @return Collection|User[]
     */
    public function byMail($email);

    /**
     * @param $company
     * @return Collection|User[]
     */
    public function byCompany($company);

    /**
     * @param $company
     * @return Collection|User[]
     */
    public function byDateRange($dstart, $dend);

    /**
     * @param array $input
     * @return Collection|User[]
     */
    public function byUnionSearch($input, $page, $per_page);
    
    /**
     * @param $id
     * @param $data
     * @return boolean
     */
    public function validUpdate($id, $data);

    /**
     * @return \Illuminate\Support\MessageBag
     */
    public function errors();

    /**
     * @param $id
     * @param $data
     * @return boolean
     */
    public function update($id, $data);

    /**
     * @return User
     */
    public function dummy();

    /**
     * @param Collection $users
     * @return Collection|User[]
     */
    public function filterExpertsWithToBeExperts(Collection $users);

    /**
     * @param Collection $users
     * @return Collection|User[]
     */
    public function filterCreatorWithoutToBeExperts(Collection $users);

    /**
     * @param Collection $users
     * @return Collection|User[]
     */
    public function filterExperts(Collection $users);

    /**
     * @param Collection $users
     * @return Collection|User[]
     */
    public function filterCreator(Collection $users);

    /**
     * @param Collection $users
     * @return Collection|User[]
     */
    public function filterPM(Collection $users);

    /**
     * @param $dstart
     * @param $dend
     * @return Collection|User[]
     */
    public function getCommentCountsByDate($dstart, $dend);

    /**
     * @param $dstart
     * @param $dend
     * @param $id
     * @return Collection|User[]
     */
    public function getCommentCountsByDateById($dstart, $dend, $id);

    /**
     * @param $dstart
     * @param $dend
     * @param $name
     * @return Collection|User[]
     */
    public function getCommentCountsByDateByName($dstart, $dend, $name);

    /**
     * @param $users
     * @return array
     */
    public function toOutputArray($users);

    /**
     * @param $user_id
     * @param $is_hwtrek_pm
     * @return void
     */
    public function changeHWTrekPM($user_id, $is_hwtrek_pm);

    /**
     * @return mixed
     * @return Collection|User[]
     */
    public function findHWTrekPM();
}
