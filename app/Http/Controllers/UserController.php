<?php

namespace Backend\Http\Controllers;

use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoInterfaces\SolutionInterface;
use Backend\Repo\RepoInterfaces\ApplyExpertMessageInterface;
use Backend\Repo\RepoInterfaces\ExpertiseInterface;
use Backend\Api\ApiInterfaces\UserApiInterface;
use Backend\Api\ApiInterfaces\UserApi\AttachmentApiInterface;
use Backend\Api\ApiInterfaces\UserApi\ProfileApiInterface;
use Backend\Model\Eloquent\Industry;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Input;
use Lang;
use Config;
use Noty;
use Redirect;
use Auth;
use Response;
use Log;
use Request;

class UserController extends BaseController
{
    protected $cert = 'user';

    private $user_repo;
    private $project_repo;
    private $solution_repo;
    private $expertise_repo;
    private $user_api;
    private $apply_msg_repo;
    private $attachment_api;
    private $profile_api;

    public function __construct(
        UserInterface $user,
        ProjectInterface $project,
        SolutionInterface $solution,
        ExpertiseInterface $expertise,
        UserApiInterface $user_api,
        ApplyExpertMessageInterface $apply_expert_message,
        AttachmentApiInterface $attachment_api,
        ProfileApiInterface $profile_api
    ) {
        parent::__construct();

        $this->user_repo      = $user;
        $this->project_repo   = $project;
        $this->solution_repo  = $solution;
        $this->expertise_repo = $expertise;
        $this->user_api       = $user_api;
        $this->apply_msg_repo = $apply_expert_message;
        $this->attachment_api = $attachment_api;
        $this->profile_api    = $profile_api;
    }

    public function showList()
    {
        if ($this->is_restricted_adminer) {
            return $this->showExperts();
        }

        $users = $this->user_repo->byPage($this->page, $this->per_page);

        return $this->showUsers($users);
    }

    public function showExperts()
    {
        $users = $this->user_repo->experts($this->page, $this->per_page);

        return $this->showUsers($users);
    }

    public function showCreators()
    {
        if ($this->is_restricted_adminer) {
            Noty::warnLang('common.no-permission');

            return Redirect::action('UserController@showList');
        }

        $users = $this->user_repo->creators($this->page, $this->per_page);

        return $this->showUsers($users);
    }

    public function showToBeExperts()
    {
//        if ($this->is_restricted_adminer) {
//            Noty::warnLang('common.no-permission');
//
//            return Redirect::action('UserController@showList');
//        }

        $users = $this->user_repo->toBeExpertMembers();

        if (!$users) {
            Noty::warnLang('user.no-pending-expert');

            return Redirect::action('UserController@showList');
        }

        return $this->showUsers($users, $paginate = false, $title = 'To-Be Expert Members');
    }

    public function showSearch()
    {
        $users = $this->user_repo->byUnionSearch(Input::all(), $this->page, $this->per_page);
        $log_action = 'Search user';
        Log::info($log_action, Input::all());

        if ($users->count() == 0) {
            Noty::warnLang('common.no-search-result');
        }

        return $this->showUsers($users, $paginate = true);
    }

    public function showUsers($users, $paginate = true, $title = '')
    {
        if (Input::has('csv')) {
            return $this->renderCsv($users);
        }

        $data = [
            'title'         => $title,
            'users'         => $users,
            'to_expert_ids' => $this->user_repo->toBeExpertMemberIds(),
        ];

        if ($this->is_limitied_editor) {
            $view = 'user.list-editor';
        } else {
            $view                  = 'user.list';
            $data['is_restricted'] = $this->is_restricted_adminer;
        }

        $template = view($view)->with($data);

        return $paginate ? $template->with('per_page', $this->per_page) : $template;
    }

    private function renderCsv($users)
    {

        if (Input::get('csv') == 'all') {
            $output = $this->user_repo->toOutputArray($this->user_repo->all());
        } else {
            $output = $this->user_repo->toOutputArray($users);
        }

        $csv_type   = Input::get('csv') == 'all' ? 'all' : 'this';
        $log_action = 'CSV of Members ('.$csv_type.')';
        Log::info($log_action);

        return $this->outputArrayToCsv($output, 'users');
        //return CSV::fromArray($output)->render('users.csv');
    }

    /**
     * show user detail, display different columns by role
     * @param $id
     * @return $this
     */
    public function showDetail($id)
    {
        $user = $this->user_repo->findWithDetail($id);

        if (is_null($user)) {
            Noty::warnLang('user.no-user');
            return Redirect::action('UserController@showList');
        }

        if ($this->is_restricted_adminer and
            !$user->isExpert()
        ) {
            Noty::warn('No access permission');

            return Redirect::action('UserController@showList');
        }

        $attachments = $this->attachment_api->getAttachment($user);
        
        $data = [
            'expertises'        => $this->expertise_repo->getTags(),
            'expertise_setting' => explode(',', $user->expertises),
            'user'              => $user,
            'projects'          => $this->project_repo->byUserId($user->user_id),
            'solutions'         => $this->solution_repo->configApprove($user->solutions),
            'apply_expert_msg'  => $this->apply_msg_repo->byUserId($user->user_id),
            'attachments'       => $attachments
        ];

        if ($this->is_limitied_editor) {
            $view = 'user.detail-editor';
        } else {
            $view                  = 'user.detail';
            $data['is_restricted'] = $this->is_restricted_adminer;
        }

        return view($view)->with($data);
    }

    /**
     * show user detail, display different columns by role
     * @param $id
     * @return $this
     */
    public function showUpdate($id, $param = null)
    {
        $user = $this->user_repo->find($id);
        if (is_null($user)) {
            Noty::warnLang('user.no-user');
            return Redirect::action('UserController@showList');
        }

        if ($this->is_restricted_adminer and !$user->isExpert()) {
            Noty::warn('No access permission');

            return Redirect::action('UserController@showList');
        }

        if ($param == 'delete-attachment-fail') {
            Noty::warn('No access permission');
            return Redirect::action('UserController@showUpdate', [$id]);
        }

        $attachments = $this->attachment_api->getAttachment($user);
        $front_domain = Config::get('app.front_domain');

        $data = [
            'industries'        => Industry::getUpdateArray(),
            'expertise_tags'    => $this->expertise_repo->getTags(),
            'user'              => $user,
            'user_industries'   => explode(',', $user->user_category_id),
            'expertise_setting' => explode(',', $user->expertises),
            'apply_expert_msg'  => $this->apply_msg_repo->byUserId($user->user_id),
            'attachments'       => $attachments,
            'front_domain'      => $front_domain
        ];

        if ($this->is_limitied_editor) {
            $view                 = 'user.update-editor';
        } else {
            $view                  = 'user.update';
            $data['is_restricted'] = $this->is_restricted_adminer;
        }

        return view($view)->with($data);
    }

    public function update($id)
    {
        $data        = Input::all();
        $user        = $this->user_repo->find($id);
        $origin_data = [
            'image'          => $user->getImagePath(),
            'biography'      => $user->user_about,
        ];
        if ($user->isExpert()) {
            $origin_data['industries']     = $user->user_category_id;
            $origin_data['expertise_tags'] = $user->expertises;
        }

        if (!$this->user_repo->validUpdate($id, $data)) {
            Noty::warn(Lang::get('user.update-fail'));

            return Redirect::action('UserController@showUpdate', [$id])
                ->withInput()
                ->withErrors($this->user_repo->errors());
        }

        $this->user_repo->update($id, $data);

        if (array_key_exists('attachments', $data)) {
            $attachments['put']    = [];
            $attachments['delete'] = [];
            $attachment_data = (json_decode($data['attachments'], true));

            if ($attachment_data) {
                foreach ($attachment_data['put_items'] as $row) {
                    $attachments['put'][] = $row;
                }
                foreach ($attachment_data['delete_items'] as $row) {
                    $attachments['delete'][] = $row;
                }
                $this->attachment_api->updateAttachment($user, $attachments);
            }
        }

        Noty::success(Lang::get('user.update'));

        $log_action = 'Edit user';
        $log_data   = [
            'user'        => $id,
            'origin_data' => $origin_data,
            'is_expert'   => $user->isExpert(),
            Input::all()
        ];
        Log::info($log_action, $log_data);

        return Redirect::action('UserController@showDetail', $id);
    }

    /*
     * Search User For Replace Project Owner
     *
     * @param int $user_id
     * @return json $data
     */
    public function searchUser($user_id)
    {
        return $this->user_api->basicColumns($user_id);
    }

    public function changeUserType()
    {
        if (Auth::user()->isAdmin()) {
            $user_id   = Input::get('user_id');
            $user_type = Input::get('user_type');
            $user      = $this->user_repo->find($user_id);
            if (count($user) > 0) {
                $this->user_repo->changeUserType($user_id, $user_type);
                $res = [ 'status' => 'success' ];
            } else {
                $res = [ 'status' => 'fail', 'msg' => 'Not found user id!' ];
            }
        } else {
            $res = [ 'status' => 'fail', 'msg' => 'Permissions denied!' ];
        }
        return Response::json($res);
    }

    /**
     * Put attachment to web service backend api
     *
     * @param              $user_id
     * @param UploadedFile $file
     * @return int|null
     */
    public function putAttachment()
    {
        $user = $this->user_repo->find(Request::get('user_id'));
        $file = Request::file()[0];
        $r    = $this->attachment_api->putAttachment($user, $file);
        Log::info('Upload attachment', (array) $r);
        return json_encode($r);
    }

    public function disable()
    {
        if (!Auth::user()->isAdmin()) {
            Noty::warnLang('common.no-permission');

            return Redirect::action('UserController@showList');
        }
        $user_id = Input::get('user_id');
        $user    = $this->user_repo->find($user_id);
        return $this->profile_api->disable($user);
    }

    public function enable()
    {
        if (!Auth::user()->isAdmin()) {
            Noty::warnLang('common.no-permission');

            return Redirect::action('UserController@showList');
        }
        $user_id = Input::get('user_id');
        $user    = $this->user_repo->find($user_id);
        return $this->profile_api->enable($user);
    }
}
