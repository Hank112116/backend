<?php

namespace Backend\Http\Controllers;

use Backend\Http\Requests;
use Backend\Http\Controllers\Controller;
use Backend\Repo\RepoInterfaces\ReportInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ReportController extends BaseController
{
    protected $cert = "report";

    public function __construct(
        ReportInterface $report_repo
    ) {
        parent::__construct();
        $this->report_repo=$report_repo;

    }

    /**
     * To show the register in the given time interval.
     * @return view Registration Summary view
     */
    public function showRegistrationReport()
    {
        $auth=Auth::user()->isAdmin()||Auth::user()->isManagerHead();
        $validator=Validator::make(Input::all(), [
            'range'     =>  'numeric|min:1',
            'dstart'    =>  'date',
            'dend'      =>  'date',
        ]);
        if ($validator->fails()) {
            \Noty::warn('The input parameter is wrong');
            return Redirect::back();
        }

        $range=null;
        if (Input::get('dstart')!=null&&Input::get('dend')!=null) {
            $dstart     =  Input::get('dstart');
            $dend       =  Input::get('dend');
        } else {
            $dstart     =   Carbon::parse(Input::get('range', 7).' days ago')->toDateString();
            $dend       =   Carbon::now()->toDateString();
            $range      =   'Register in last '.Input::get('range', 7).' days.';
        }

        $filter = $auth?Input::get('filter', 'all'):'expert';

        $users  = $this->report_repo->getRegisters(compact('dstart', 'dend', 'filter'));

        $template = view('report.registration')
            ->with([
                'title'         => 'Registration Summary',
                'users'         => $users,
                'range'         => $range,
                'is_restricted' => !$auth,
            ]);

        return $template;
    }
}
