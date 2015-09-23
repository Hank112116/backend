<?php

namespace Backend\Http\Controllers;

use CSV;
use Illuminate\Database\Eloquent\Collection;
use Backend\Repo\RepoInterfaces\UserInterface;

use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoInterfaces\ProductInterface;
use Backend\Repo\RepoInterfaces\SolutionInterface;

use Backend\Repo\RepoInterfaces\ExpertiseInterface;
use Backend\Api\ApiInterfaces\UserApiInterface;
use Backend\Model\Eloquent\Industry;

use Input;
use Lang;
use Noty;
use Redirect;
use Auth;
use Response;
use Log;

class UserController extends BaseController
{

    protected $cert = 'user';

    public function __construct(
        UserInterface $user,
        ProjectInterface $project,
        ProductInterface $product,
        SolutionInterface $solution,
        ExpertiseInterface $expertise,
        UserApiInterface $user_api
    ) {
        parent::__construct();

        $this->user_repo = $user;

        $this->project_repo = $project;
        $this->product_repo = $product;

        $this->solution_repo  = $solution;
        $this->expertise_repo = $expertise;

        $this->user_api = $user_api;
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
            'id'      => Input::get('user_id') ? : null,
            'name'    => Input::get('name') ? : null,
            'email'   => Input::get('email') ? : null,
            'company' => Input::get('company') ? : null,
            'data'    => Input::get('dstart') ? Input::get('dstart').'~'.Input::get('dend') : null,
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
        $template = view('user.list')
            ->with([
                'title'         => $title,
                'users'         => $users,
                'to_expert_ids' => $this->user_repo->toBeExpertMemberIds(),
                'is_restricted' => $this->is_restricted_adminer,
            ]);

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

        if ($this->is_restricted_adminer and
            !$user->isExpert()
        ) {
            Noty::warn('No access permission');

            return Redirect::action('UserController@showList');
        }

        return view('user.detail')->with([
            'is_restricted'     => $this->is_restricted_adminer,
            'expertises'        => $this->expertise_repo->getTags(),
            'expertise_setting' => explode(',', $user->expertises),
            'user'              => $user,
            'projects'          => $this->project_repo->byUserId($user->user_id),
            'products'          => $this->product_repo->byUserId($user->user_id),
            'solutions'         => $this->solution_repo->configApprove($user->solutions)
        ]);
    }

    /**
     * show user detail, display different columns by role
     * @param $id
     * @return $this
     */
    public function showUpdate($id)
    {
        $user = $this->user_repo->find($id);

        if ($this->is_restricted_adminer and
            !$user->isExpert()
        ) {
            Noty::warn('No access permission');

            return Redirect::action('UserController@showList');
        }

        return view('user.update')->with([
            'is_restricted'     => $this->is_restricted_adminer,
            'industries'        => Industry::getUpdateArray(),
            'expertise_tags'    => $this->expertise_repo->getTags(),
            'user'              => $user,
            'user_industries'   => explode(',', $user->user_category_id),
            'expertise_setting' => explode(',', $user->expertises)
        ]);
    }

    public function update($id)
    {
        $data = Input::all();

        if (!$this->user_repo->validUpdate($id, $data)) {
            Noty::warn(Lang::get('user.update-fail'));

            return Redirect::action('UserController@showUpdate', [$id])
                ->withInput()
                ->withErrors($this->user_repo->errors());
        }

        $log_action = 'Edit user';
        $log_data   = [
            'user'      => $id,
            'is_expert' => $data['user_type']==1 ? true : false
        ];
        Log::info($log_action, $log_data);

        $this->user_repo->update($id, $data);
        Noty::success(Lang::get('user.update'));

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
}
