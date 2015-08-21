<?php

namespace Backend\Http\Controllers;

use Backend\Http\Requests;
use Backend\Http\Controllers\Controller;
use Backend\Repo\RepoInterfaces\UserInterface;
use Noty;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ReportController extends BaseController
{
    protected $cert = "report";
    protected $page;
    protected $per_page;
    private $auth;
    private $user_repo;
    private $search_name;
    private $range;
    private $validator;
    private $filter;

    public function __construct(
        UserInterface $user_repo
    ) {
        parent::__construct();
        $this->auth        = Auth::user()->isAdmin() || Auth::user()->isManagerHead();
        $this->user_repo   = $user_repo;
        $this->page        = Input::get('page', 1);
        $this->per_page    = Input::get('pp', 50);
        $this->search_name = Input::get('name');
        $this->getTimeInterval();
        $this->getFilter();
    }

    /**
     * @return Validator The validator for date range and custom interval
     */
    private function makeValidator()
    {
        return Validator::make(Input::all(), [
            'range'  => 'integer|min:1|required_without_all:dstart,dend',
            'dstart' => 'date|required_with:dend',
            'dend'   => 'date|required_with:dstart',
        ]);

    }

    /**
     * Get time interval from Input(range, dstart,and dend) and set $this->range, $this->dstart, $this->dend
     *
     * @return mixed
     */
    private function getTimeInterval()
    {
        $this->validator = $this->makeValidator();
        $this->range     = null;
        if (!$this->validator->fails()) {
            if (Input::get('dstart') != null && Input::get('dend') != null) {
                $this->dstart = Input::get('dstart');
                $this->dend   = Input::get('dend');
            } else {
                $this->dstart = Carbon::parse(Input::get('range', 7) . ' days ago')->toDateString();
                $this->dend   = Carbon::now()->toDateString();
                $this->range  = Input::get('range', 7);
            }
        }
    }

    /**
     * Get the filter from Input['filter'] and set $this->filter
     */
    private function getFilter()
    {
        $this->filter = $this->auth ? Input::get('filter', 'all') : 'expert';
    }

    public function showCommentReport()
    {

        if (isset($this->validator) && $this->validator->fails()) {
            Noty::warn('The input parameter is wrong');
            return Redirect::back();
        }

        $users = $this->user_repo->withCommentCountsByDate($this->dstart, $this->dend);
        if (isset($this->search_name) && $this->search_name != '') {
            $users = $users->byName($this->search_name);
        } else {
            $users = $users->get();
        }
        if ($this->filter === 'expert') {
            $users = $this->user_repo->filterExperts($users);
        }
        if ($this->filter === 'creator') {
            $users = $this->user_repo->filterCreator($users);
        }
        if ($this->filter === 'pm') {
            $users = $this->user_repo->filterPM($users);
        }
        $users    = $this->user_repo->byCollectionPage($users, $this->page, $this->per_page);
        $template = view('report.comment')
            ->with([
                'title'          => 'Comment Summary',
                'users'          => $users,
                'range'          => $this->range,
                'is_super_admin' => $this->auth,
            ]);
        return $template;
    }

    /**
     * To show the register in the given time interval.
     *
     * @return view Registration Summary view
     */
    public function showRegistrationReport()
    {
        if (isset($this->validator) && $this->validator->fails()) {
            Noty::warn('The input parameter is wrong');
            return Redirect::back();
        }

        $users = $this->user_repo->byDateRange($this->dstart, $this->dend);

        if ($this->filter === 'expert') {
            $users = $this->user_repo->filterExpertsWithToBeExperts($users);
        }
        if ($this->filter === 'creator') {
            $users = $this->user_repo->filterCreatorWithoutToBeExperts($users);
        }

        $users    = $this->user_repo->byCollectionPage($users, $this->page, $this->per_page);
        $template = view('report.registration')
            ->with([
                'title'          => 'Registration Summary',
                'users'          => $users,
                'range'          => $this->range,
                'is_super_admin' => $this->auth,
            ]);

        return $template;
    }
}
