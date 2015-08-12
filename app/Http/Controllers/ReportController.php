<?php

namespace Backend\Http\Controllers;

use Backend\Http\Requests;
use Backend\Http\Controllers\Controller;
use Backend\Repo\RepoInterfaces\ReportInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class ReportController extends BaseController
{
    protected $cert = "report";

    public function __construct(
        ReportInterface $report_repo,
        UserInterface $user_repo
    ) {
        parent::__construct();
        $this->report_repo=$report_repo;
        $this->user_repo=$user_repo;
    }
    public function showRegistrationReport()
    {
        $users = $this->report_repo->getRegisters(Input::all());

        if ($users->count() == 0) {
            \Noty::warn('No result');
            return Redirect::back();
        }

        $template = view('report.registration')
            ->with([
                'title'         => 'Registration Summary',
                'users'         => $users,
                'is_restricted' => false,
            ]);

        return $template;
    }
}
