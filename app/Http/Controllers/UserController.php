<?php

namespace Backend\Http\Controllers;

use Backend\Assistant\ApiResponse\UserApi\UserAttachmentResponseAssistant;
use Backend\Exceptions\Api\CompanyLogoApiException;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoInterfaces\SolutionInterface;
use Backend\Repo\RepoInterfaces\ApplyExpertMessageInterface;
use Backend\Repo\RepoInterfaces\ExpertiseInterface;
use Backend\Api\ApiInterfaces\UserApiInterface;
use Backend\Api\ApiInterfaces\UserApi\AttachmentApiInterface;
use Backend\Api\ApiInterfaces\UserApi\ProfileApiInterface;
use Backend\Model\Eloquent\Industry;
use Backend\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Noty;

class UserController extends BaseController
{
    protected $cert = 'user';

    private $user_repo;
    private $project_repo;
    private $solution_repo;
    private $expertise_repo;
    private $user_api;
    private $apply_msg_repo;

    public function __construct(
        UserInterface $user,
        ProjectInterface $project,
        SolutionInterface $solution,
        ExpertiseInterface $expertise,
        UserApiInterface $user_api,
        ApplyExpertMessageInterface $apply_expert_message
    ) {
        parent::__construct();
        $this->user_repo      = $user;
        $this->project_repo   = $project;
        $this->solution_repo  = $solution;
        $this->expertise_repo = $expertise;
        $this->user_api       = $user_api;
        $this->apply_msg_repo = $apply_expert_message;
        $this->per_page       = 200;
    }

    public function showList()
    {
        if ($this->isRestricted()) {
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
        if ($this->isRestricted()) {
            Noty::warnLang('common.no-permission');

            return redirect()->action('UserController@showList');
        }

        $users = $this->user_repo->creators($this->page, $this->per_page);

        return $this->showUsers($users);
    }

    public function showToBeExperts()
    {
//        if ($this->is_restricted_adminer) {
//            Noty::warnLang('common.no-permission');
//
//            return redirect()->action('UserController@showList');
//        }

        $users = $this->user_repo->toBeExpertMembers();

        if (!$users) {
            Noty::warnLang('user.no-pending-expert');

            return redirect()->action('UserController@showList');
        }

        return $this->showUsers($users, $paginate = false, $title = 'To-Be Expert Members');
    }

    public function showSearch()
    {
        $users = $this->user_repo->byUnionSearch($this->request->all(), $this->page, $this->per_page);
        $log_action = 'Search user';
        Log::info($log_action, $this->request->all());

        if ($users->count() == 0) {
            Noty::warnLang('common.no-search-result');
        }

        return $this->showUsers($users, $paginate = true);
    }

    public function showUsers($users, $paginate = true, $title = '')
    {
        if ($this->request->has('csv')) {
            return $this->renderCsv($users);
        }

        $data = [
            'title'         => $title,
            'users'         => $users,
            'to_expert_ids' => $this->user_repo->toBeExpertMemberIds(),
        ];

        if ($this->isLimitedEditor()) {
            $view = 'user.list-editor';
        } else {
            $view                  = 'user.list';
            $data['is_restricted'] = $this->isRestricted();
        }

        $template = view($view)->with($data);

        return $paginate ? $template->with('per_page', $this->per_page) : $template;
    }

    private function renderCsv($users)
    {

        if ($this->request->get('csv') == 'all') {
            $output = $this->user_repo->toOutputArray($this->user_repo->all());
        } else {
            $output = $this->user_repo->toOutputArray($users);
        }

        $csv_type   = $this->request->get('csv') == 'all' ? 'all' : 'this';
        $log_action = 'CSV of Members ('.$csv_type.')';
        Log::info($log_action);

        return $this->outputArrayToCsv($output, 'users');
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
            return redirect()->action('UserController@showList');
        }

        if ($this->isRestricted() and
            !$user->isExpert()
        ) {
            Noty::warn('No access permission');

            return redirect()->action('UserController@showList');
        }

        /* @var AttachmentApiInterface $attachment_api */
        $attachment_api    = app()->make(AttachmentApiInterface::class);
        $attachment_assist = UserAttachmentResponseAssistant::create($attachment_api->getAttachment($user));
        $attachments       = $attachment_assist->getAttachments();

        $data = [
            'expertises'        => $this->expertise_repo->getTags(),
            'expertise_setting' => explode(',', $user->expertises),
            'user'              => $user,
            'projects'          => $this->project_repo->byUserId($user->user_id),
            'solutions'         => $this->solution_repo->configApprove($user->solutions),
            'apply_expert_msg'  => $this->apply_msg_repo->byUserId($user->user_id),
            'attachments'       => $attachments
        ];

        if ($this->isLimitedEditor()) {
            $view = 'user.detail-editor';
        } else {
            $view                  = 'user.detail';
            $data['is_restricted'] = auth()->user()->isRestricted($this->cert);
        }

        return view($view, $data);
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
            return redirect()->action('UserController@showList');
        }

        if ($this->isRestricted() and !$user->isExpert()) {
            Noty::warn('No access permission');

            return redirect()->action('UserController@showList');
        }

        if ($param == 'delete-attachment-fail') {
            Noty::warn('No access permission');
            return redirect()->action('UserController@showUpdate', [$id]);
        }

        /* @var AttachmentApiInterface $attachment_api */
        $attachment_api    = app()->make(AttachmentApiInterface::class);
        $attachment_assist = UserAttachmentResponseAssistant::create($attachment_api->getAttachment($user));
        $attachments       = $attachment_assist->getAttachments();

        $front_domain   = config('app.front_domain');

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

        if ($this->isLimitedEditor()) {
            $view                 = 'user.update-editor';
        } else {
            $view                  = 'user.update';
            $data['is_restricted'] = $this->isRestricted();
        }

        return view($view, $data);
    }

    public function update($id)
    {
        $data        = $this->request->all();
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
            Noty::warn(trans('user.update-fail'));

            return redirect()->action('UserController@showUpdate', [$id])
                ->withInput()
                ->withErrors($this->user_repo->errors());
        }

        try {
            $this->user_repo->update($id, $data);
        } catch (CompanyLogoApiException $e) {
            Log::error($e->getMessage(), $e->getTrace());

            Noty::warn($e->getMessage());

            return redirect()->action('UserController@showUpdate', [$id])
                ->withInput();
        }

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
                /* @var AttachmentApiInterface $attachment_api*/
                $attachment_api = app()->make(AttachmentApiInterface::class);
                $r = $attachment_api->updateAttachment($user, $attachments);
            }
        }

        //Noty::success(trans('user.update'));

        $log_action = 'Edit user';
        $log_data   = [
            'user'        => $id,
            'origin_data' => $origin_data,
            'is_expert'   => $user->isExpert(),
            $this->request->all()
        ];
        Log::info($log_action, $log_data);

        return redirect()->action('UserController@showDetail', $id);
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
        if (!auth()->user()->isAdmin()) {
            $res = [ 'status' => 'fail', 'msg' => 'Permissions denied!' ];
            return response()->json($res);
        }
        $user_id   = $this->request->get('user_id');
        $user_type = $this->request->get('user_type');
        $user      = $this->user_repo->find($user_id);
        if (!$user) {
            $res = [ 'status' => 'fail', 'msg' => 'Not found user id!' ];
            return response()->json($res);
        }

        $this->user_repo->changeUserType($user_id, $user_type);
        Log::info('Change user type', [
            'user_id'   => $user_id,
            'user_type' => $user_type
        ]);
        $user = $this->user_repo->find($user_id);
        $view = view()->make('user.row')->with(
            [
                'user'          => $user,
                'is_restricted' => $this->isRestricted(),
                'tag_tree'      => $this->expertise_repo->getTags()
            ]
        )->render();
        $res = [ 'status' => 'success', 'view' => $view ];
        return response()->json($res);
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
        $user = $this->user_repo->find($this->request->get('user_id'));
        $file = $this->request->file()[0];
        /* @var AttachmentApiInterface $attachment_api */
        $attachment_api      = app()->make(AttachmentApiInterface::class);
        $response_assistant  = UserAttachmentResponseAssistant::create($attachment_api->putAttachment($user, $file));
        Log::info('Upload attachment', $response_assistant->decode());
        return json_encode($response_assistant->getAttachment());
    }

    public function disable()
    {
        if (!auth()->user()->isAdmin()) {
            Noty::warnLang('common.no-permission');

            return redirect()->action('UserController@showList');
        }
        $user_id = $this->request->get('user_id');
        $user    = $this->user_repo->find($user_id);

        /* @var ProfileApiInterface $profile_api */
        $profile_api = app()->make(ProfileApiInterface::class);

        Log::info('Suspend User ' . $user_id, ['user_id' => $user_id]);

        return $profile_api->disable($user);
    }

    public function enable()
    {
        if (!auth()->user()->isAdmin()) {
            Noty::warnLang('common.no-permission');

            return redirect()->action('UserController@showList');
        }
        $user_id = $this->request->get('user_id');
        $user    = $this->user_repo->find($user_id);

        /* @var ProfileApiInterface $profile_api */
        $profile_api = app()->make(ProfileApiInterface::class);

        Log::info('Unsuspend User ' . $user_id, ['user_id' => $user_id]);

        return $profile_api->enable($user);
    }

    public function updateMemo()
    {
        $input = $this->request->all();
        if ($this->user_repo->updateInternalMemo($input['user_id'], $input)) {
            $user = $this->user_repo->find($input['user_id']);
            if ($input['route_path'] === 'report/registration') {
                // make report project row view
                $view = view()->make('report.user-row')
                    ->with(['user' => $user, 'input' => $input,'is_super_admin' => $this->isSuperAdmin()])
                    ->render();
            } else {
                if ($this->isLimitedEditor()) {
                    $view = 'user.editor-row';
                } else {
                    $view = 'user.row';
                }
                // make project row view
                $view = view()->make($view)->with(
                    [
                        'user'          => $user,
                        'is_restricted' => $this->isRestricted(),
                        'tag_tree'      => $this->expertise_repo->getTags()
                    ]
                )->render();
            }

            $res  = ['status' => 'success', 'view' => $view];
        } else {
            $res   = ['status' => 'fail', "msg" => "Update Fail!"];
        }

        $log_action = 'Edit user internal memo';
        Log::info($log_action, $input);

        return response()->json($res);
    }
}
