<?php

namespace Backend\Http\Controllers;

use Backend\Http\Requests;
use Backend\Http\Controllers\Controller;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoTrait\PaginateTrait;
use Noty;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ReportController extends BaseController
{
    protected $cert = "report";

    public function __construct(
        UserInterface $user_repo
    ) {
        parent::__construct();
        $this->user_repo    = $user_repo;
        $this->page         = Input::get('page', 1);
        $this->per_page     = Input::get('pp', 15);

    }

    /**
     * To show the register in the given time interval.
     *
     * @return view Registration Summary view
     */
    public function showRegistrationReport()
    {
        $auth = Auth::user()->isAdmin() || Auth::user()->isManagerHead();
        $validator = Validator::make(Input::all(), [
            'range'     => 'numeric|required_without_all:dstart,dend',
            'dstart'    => 'date|required_with:dend',
            'dend'      => 'date|required_with:dstart',
        ]);
        if ($validator->fails()) {
            Noty::warn('The input parameter is wrong');
            return Redirect::back();
        }
        $range = null;
        if (Input::get('dstart') != null && Input::get('dend') != null) {
            $dstart = Input::get('dstart');
            $dend   = Input::get('dend');
        } else {
            $dstart = Carbon::parse(Input::get('range', 7) . ' days ago')->toDateString();
            $dend   = Carbon::now()->toDateString();
            $range  = 'Register in last ' . Input::get('range', 7) . ' days.';
        }

        $filter = $auth ? Input::get('filter', 'all') : 'expert';

        $users = $this->user_repo->byDateRange($dstart, $dend);

        if ($filter === 'expert') {
            $users = $this->user_repo->filterExperts($users);
        }
        if ($filter === 'creator') {
            $users = $this->user_repo->filterCreator($users);
        }

        $users = $this->user_repo->byCollectionPage($users, $this->page, $this->per_page);
        $template = view('report.registration')
            ->with([
                'title' => 'Registration Summary',
                'users' => $users,
                'range' => $range,
                'is_restricted' => !$auth,
            ]);

        return $template;
    }
}
