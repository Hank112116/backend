<?php

namespace Backend\Http\Controllers;

use CSV;
use Illuminate\Database\Eloquent\Collection;
use Backend\Repo\RepoInterfaces\UserInterface;

use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoInterfaces\ProductInterface;
use Backend\Repo\RepoInterfaces\SolutionInterface;
use Backend\Repo\RepoInterfaces\ApplyExpertMessageInterface;

use Backend\Repo\RepoInterfaces\ExpertiseInterface;
use Backend\Api\ApiInterfaces\UserApiInterface;
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
use Curl\Curl;
use Request;
use RSA;

class UserController extends BaseController
{
    protected $cert = 'user';

    public function __construct(
        UserInterface $user,
        ProjectInterface $project,
        ProductInterface $product,
        SolutionInterface $solution,
        ExpertiseInterface $expertise,
        UserApiInterface $user_api,
        ApplyExpertMessageInterface $apply_expert_message
    ) {
        parent::__construct();

        $this->user_repo      = $user;
        $this->project_repo   = $project;
        $this->product_repo   = $product;
        $this->solution_repo  = $solution;
        $this->expertise_repo = $expertise;
        $this->user_api       = $user_api;
        $this->apply_msg_repo = $apply_expert_message;
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

    public function showSearch($search_by)
    {
        switch ($search_by) {
            case 'user_id':
                $users = $this->user_repo->byId(Input::get('user_id'));
                break;

            case 'name':
                $users = $this->user_repo->byName(Input::get('name'));
                break;

            case 'email':
                $users = $this->user_repo->byMail(Input::get('email'));
                break;

            case 'company':
                $users = $this->user_repo->byCompany(Input::get('company'));
                break;

            case 'date':
                $users = $this->user_repo->byDateRange(Input::get('dstart'), Input::get('dend'));
                break;

            default:
                $users = new Collection();
        }

        if ($this->is_restricted_adminer) {
            $users = $this->user_repo->filterExperts($users);
        }

        $log_action = 'Search by '.$search_by;
        $log_data   = [
            'id'      => Input::get('user_id'),
            'name'    => Input::get('name'),
            'email'   => Input::get('email'),
            'company' => Input::get('company'),
            'dstart'  => Input::get('dstart'),
            'dend'    => Input::get('dend'),
            'result'  => sizeof($users)
        ];
        Log::info($log_action, $log_data);

        if ($users->count() == 0) {
            Noty::warn('No result');

            return Redirect::action('UserController@showList');
        } else {
            return $this->showUsers($users, $paginate = false);
        }
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

        $attachments = $this->getUserAttachment($id);

        $data = [
            'expertises'        => $this->expertise_repo->getTags(),
            'expertise_setting' => explode(',', $user->expertises),
            'user'              => $user,
            'projects'          => $this->project_repo->byUserId($user->user_id),
            'products'          => $this->product_repo->byUserId($user->user_id),
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

        $attachments  = $this->getUserAttachment($id);
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
        Noty::success(Lang::get('user.update'));

        $log_action = 'Edit user';
        $log_data   = [
            'user'        => $id,
            'origin_data' => $origin_data,
            'is_expert'   => $user->isExpert()
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

    public function changeHWTrekPM()
    {
        if (Auth::user()->isAdmin()) {
            $user_id      = Input::get('user_id');
            $is_hwtrek_pm = Input::get('is_hwtrek_pm');
            $user = $this->user_repo->find($user_id);
            if (count($user) > 0) {
                $this->user_repo->changeHWTrekPM($user_id, $is_hwtrek_pm);
                $res   = ['status' => 'success'];
            } else {
                $res   = ['status' => 'fail', 'msg'=>'Not found user id!'];
            }
        } else {
            $res   = ['status' => 'fail', 'msg'=>'Permissions denied!'];
        }
        return Response::json($res);
    }

    /**
     * Get web service profile api return attachments information
     *
     * @param $user_id
     * @return object attachments
     */
    private function getUserAttachment($user_id)
    {
        $front_domain = Config::get('app.front_domain');
        $curl = new Curl();
        $curl->setReferrer('https://' . $front_domain);
        $curl->setHeader('X-Requested-With', 'XMLHttpRequest');
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $r = $curl->get("https://{$front_domain}/apis/users/{$user_id}/profile");
        return $r->attachments;
    }

    /**
     * Create attachment
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAttachment()
    {
        $user_id                 = Request::get('user_id');
        $attachment              = $this->putAttachment($user_id, Request::file()[0]);
        $attachments['put'][]    = $attachment;
        $attachments['delete']   = [];
        $http_code               = $this->updateAttachment($user_id, $attachments);
        Log::info('update attachment', array($attachment));
        return Response::json('', $http_code);
    }

    /**
     * Delete attachment
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAttachment()
    {
        $user_id                 = Request::get('user_id');
        $attachment              = json_decode(base64_decode(Request::get('attachment')));
        $attachments['delete'][] = $attachment;
        $attachments['put']      = [];
        $http_code               = $this->updateAttachment($user_id, $attachments);
        Log:info('delete attachment', array($attachment));
        return Response::json('', $http_code);
    }

    /**
     * Put attachment to web service backend api
     *
     * @param              $user_id
     * @param UploadedFile $file
     * @return int|null
     */
    private function putAttachment($user_id, UploadedFile $file)
    {
        $front_domain   = Config::get('app.front_domain');
        $backend_domain = Config::get('app.backend_domain');

        $upload_dir = '/tmp/';

        $file->move($upload_dir, $file->getClientOriginalName());

        $file_path = $upload_dir . $file->getClientOriginalName();
        $fp        = fopen($file_path, "r");

        $curl = new Curl();
        $curl->setReferrer('https://' . $backend_domain);
        $curl->setHeader('X-Requested-With', 'XMLHttpRequest');
        $curl->setHeader('Content-Type', 'multipart/form-data');
        $curl->setHeader('Accept', 'application/json');
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOpt(CURLOPT_INFILE, $fp);
        $curl->setOpt(CURLOPT_INFILESIZE, filesize($file_path));

        $curl->put("https://{$front_domain}/apis/backend/users/{$user_id}/attachments", [
            'file'      => "@{$file_path}",
            'pass_code' =>  RSA::encryption(Config::get('app.pass_code'), Config::get('front-public-key'))
        ]);

        fclose($fp);
        unlink($file_path);

        if ($curl->error) {
            return $curl->errorCode;
        }
        $response = $curl->response;
        $curl->close();
        return $response;
    }

    /**
     * Update attachment to web service backend api
     *
     * @param $user_id
     * @param $attachments
     * @return int
     */
    private function updateAttachment($user_id, $attachments)
    {
        $front_domain   = Config::get('app.front_domain');
        $backend_domain = Config::get('app.backend_domain');
        $curl = new Curl();
        $curl->setReferrer('https://' . $backend_domain);
        $curl->setHeader('X-Requested-With', 'XMLHttpRequest');
        $curl->setHeader('Content-Type', 'application/json');
        $curl->setHeader('Accept', 'application/json');
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $data['attachments'] = $attachments;
        $data['pass_code']   = RSA::encryption(Config::get('app.pass_code'), Config::get('front-public-key'));
        $curl->patch("https://{$front_domain}/apis/backend/users/{$user_id}/attachments", json_encode($data));
        if ($curl->error) {
            $info['message'] =  'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage;
            Log::error('attachment update error', $info);
            $http_code = $curl->errorCode;
        } else {
            $http_code =  $curl->httpStatusCode;
        }
        $curl->close();
        return $http_code;
    }
}
