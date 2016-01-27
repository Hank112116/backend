<?php

namespace Backend\Http\Controllers;

use Backend\Http\Requests;
use Backend\Repo\RepoInterfaces\ReportInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoInterfaces\EventApplicationInterface;
use Noty;
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
    private $report_repo;
    private $filter;
    private $event_repo;

    public function __construct(
        UserInterface $user_repo,
        ReportInterface $report_repo,
        EventApplicationInterface $event_repo
    ) {
        parent::__construct();
        $this->auth        = Auth::user()->isAdmin() || Auth::user()->isManagerHead();
        $this->user_repo   = $user_repo;
        $this->report_repo = $report_repo;
        $this->event_repo  = $event_repo;
        $this->page        = Input::get('page', 1);
        $this->per_page    = Input::get('pp', 50);
    }

    /**
     * @return Validator The validator for date range and custom interval
     */
    private function dateValidator()
    {
        return Validator::make(Input::all(), [
            'range'  => 'integer|min:1|required_without_all:dstart,dend',
            'dstart' => 'date|required_with:dend',
            'dend'   => 'date|required_with:dstart',
        ]);

    }


    public function showCommentReport()
    {
        $this->filter = Input::get('filter', 'all');

        if ($this->dateValidator()->fails()) {
            Noty::warn('The input parameter is wrong');
            return Redirect::back();
        }

        $users = $this->report_repo->getCommentReport($this->filter, Input::all(), $this->page, $this->per_page);

        $template = view('report.comment')
            ->with([
                'title'          => 'Comment Summary',
                'users'          => $users,
                'range'          => Input::get('range'),
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
        $this->filter = $this->auth ? Input::get('filter', 'all') : 'expert';

        if ($this->dateValidator()->fails()) {
            Noty::warn('The input parameter is wrong');
            return Redirect::back();
        }

        $users = $this->report_repo->getRegistrationReport($this->filter, Input::all(), $this->page, $this->per_page);

        $template = view('report.registration')
            ->with([
                'title'          => 'Registration Summary',
                'users'          => $users,
                'range'          => Input::get('range'),
                'is_super_admin' => $this->auth,
            ]);
        return $template;
    }

    public function showEventReport($event_id = null)
    {
        if (is_null($event_id)) {
            $event_id = $this->event_repo->getDefaultEvent();
        }

        if (is_null(Input::get('complete'))) {
            $complete = 1;
        } else {
            $complete = Input::get('complete');
        }

        $approve  = Input::get('approve') ? Input::get('approve') : null;

        $view     = $complete ? 'report.event-complete' : 'report.event-incomplete';

        $event_list       = $this->event_repo->getEvents();
        $join_event_users = $this->report_repo->getEventReport($event_id, $complete, Input::all(), $this->page, $this->per_page);
        $join_event_users = $this->report_repo->getEventStatistics($complete, $join_event_users);

        $template = view($view)
            ->with([
                'title'            => $event_list[$event_id]['orig'] . ' Summary',
                'event_short_name' => $event_list[$event_id]['short'],
                'event_users'      => $join_event_users,
                'event_list'       => $event_list,
                'event_id'         => $event_id,
                'complete'         => $complete,
                'approve'          => $approve,
                'is_super_admin'   => $this->auth,
            ]);
        return $template;
    }

    public function updateEventNote()
    {
        if ($this->event_repo->updateEventNote(Input::get('id'), Input::get('note'))) {
            $result['status'] = 'success';
        } else {
            $result['status'] = 'fail';
        }
        return json_encode($result);
    }

    public function approveEventUser()
    {
        if ($this->event_repo->approveEventUser(Input::get('id'))) {
            $result['status'] = 'success';
        } else {
            $result['status'] = 'fail';
        }
        return json_encode($result);
    }
}
