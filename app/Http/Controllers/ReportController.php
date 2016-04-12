<?php

namespace Backend\Http\Controllers;

use Backend\Http\Requests;
use Backend\Repo\RepoInterfaces\ReportInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoInterfaces\EventApplicationInterface;
use Backend\Repo\RepoInterfaces\EventQuestionnaireInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Carbon;
use Noty;

class ReportController extends BaseController
{
    protected $cert = "report";
    private $auth;
    private $user_repo;
    private $report_repo;
    private $filter;
    private $event_repo;
    private $questionnaire_repo;

    public function __construct(
        UserInterface               $user_repo,
        ReportInterface             $report_repo,
        EventApplicationInterface   $event_repo,
        EventQuestionnaireInterface $questionnaire_repo
    ) {
        parent::__construct();
        $this->auth               = Auth::user()->isAdmin() || Auth::user()->isManagerHead();
        $this->user_repo          = $user_repo;
        $this->report_repo        = $report_repo;
        $this->event_repo         = $event_repo;
        $this->questionnaire_repo = $questionnaire_repo;
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

        $begin_number = $join_event_users->total() - (($this->page -1) * $this->per_page);

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
                'begin_number'     => $begin_number
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
        if ($this->event_repo->approveEventUser(Input::get('user_id'))) {
            $result['status'] = 'success';
        } else {
            $result['status'] = 'fail';
        }
        return json_encode($result);
    }

    public function showQuestionnaire()
    {
        if (Input::get('event_id')) {
            $event_id = Input::get('event_id');
        } else {
            $event_id = $this->event_repo->getDefaultEvent();
        }
        $event_list     = $this->event_repo->getEvents();
        $approve_event_users = $this->report_repo->getQuestionnaireReport($event_id, Input::all(), $this->page, $this->per_page);
        $view           = $this->questionnaire_repo->getView($event_id);
        $template = view($view)
            ->with([
                'title'               => $event_list[$event_id]['orig'],
                'event_short_name'    => $event_list[$event_id]['short'],
                'event_list'          => $event_list,
                'event_id'            => $event_id,
                'approve_event_users' => $approve_event_users,
                'is_super_admin'      => $this->auth
            ]);
        return $template;
    }

    public function showUserQuestionnaire()
    {
        $questionnaire = $this->questionnaire_repo->find(Input::get('questionnaire_id'));
        $questionnaire_column = $this->questionnaire_repo->getQuestionnaireColumn($questionnaire->subject_id);
        $questionnaire_items  = json_decode($questionnaire->detail, true);
        $template = view('report.event-questionnaire')
            ->with([
                'questionnaire_column' => $questionnaire_column,
                'questionnaire_items'  => $questionnaire_items
            ]);
        return $template;
    }

    public function showProjectReport()
    {
        if ($this->dateValidator()->fails()) {
            Noty::warn('The input parameter is wrong');
            return Redirect::back();
        }

        $input = Input::all();

        if (empty($input['time_type'])) {
            $input['time_type'] = 'match';
        }

        if (Input::get('range')) {
            $input['dstart']    = Carbon::parse(Input::get('range') . ' days ago')->toDateString();
        }

        if (empty(Input::get('range')) && empty(Input::get('dstart'))) {
            $input['dstart']    = Carbon::parse('7 days ago')->toDateString();
        }

        if (empty($input['dend'])) {
            $input['dend']      = Carbon::parse('1 days ago')->toDateString();
        }

        $hwtrek_pms = $this->user_repo->findHWTrekPM();

        $pm_ids = [];
        if ($hwtrek_pms) {
            foreach ($hwtrek_pms as $pm) {
                $pm_ids[] = $pm->user_id;
            }
        }

        $projects = $this->report_repo->getProjectReport($input, $this->page, $this->per_page);
        if ($input['time_type'] == 'match') {
            $match_statistics = $this->report_repo->getProjectMatchFromPM($projects, $input['dstart'], $input['dend']);
        } else {
            $match_statistics = $this->report_repo->getProjectMatchFromPM($projects);
        }

        $template = view('report.project')
            ->with([
                'title'            => 'Project Report',
                'projects'         => $projects,
                'range'            => Input::get('range'),
                'is_super_admin'   => $this->auth,
                'pm_ids'           => $pm_ids,
                'input'            => $input,
                'match_statistics' => $match_statistics

            ]);
        return $template;
    }
}
