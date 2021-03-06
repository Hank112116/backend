<?php

namespace Backend\Http\Controllers;

use Backend\Api\ApiInterfaces\EventApi\QuestionnaireApiInterface;
use Backend\Enums\EventEnum;
use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Repo\RepoInterfaces\EventReportInterface;
use Backend\Repo\RepoInterfaces\ReportInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoInterfaces\EventApplicationInterface;
use Backend\Repo\RepoInterfaces\EventQuestionnaireInterface;
use Backend\Facades\Log;
use Carbon\Carbon;
use Noty;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends BaseController
{
    protected $cert = "report";
    private $adminer_repo;
    private $user_repo;
    private $report_repo;
    private $filter;
    private $event_repo;
    private $questionnaire_repo;
    private $event_report_repo;

    public function __construct(
        AdminerInterface            $adminer_repo,
        UserInterface               $user_repo,
        ReportInterface             $report_repo,
        EventApplicationInterface   $event_repo,
        EventQuestionnaireInterface $questionnaire_repo,
        EventReportInterface        $event_report_repo
    ) {
        parent::__construct();
        $this->adminer_repo       = $adminer_repo;
        $this->user_repo          = $user_repo;
        $this->report_repo        = $report_repo;
        $this->event_repo         = $event_repo;
        $this->questionnaire_repo = $questionnaire_repo;
        $this->event_report_repo  = $event_report_repo;
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator The validator for date range and custom interval
     */
    private function dateValidator()
    {
        return validator($this->request->all(), [
            'range'  => 'integer|min:1|required_without_all:dstart,dend',
            'dstart' => 'date|required_with:dend',
            'dend'   => 'date|required_with:dstart',
        ]);
    }

    public function showCommentReport()
    {
        $this->filter = $this->request->get('filter', 'all');

        if ($this->dateValidator()->fails()) {
            Noty::warn('The input parameter is wrong');
            return redirect()->back();
        }

        $users = $this->report_repo->getCommentReport($this->filter, $this->request->all(), $this->page, $this->per_page);

        Log::info('Search comment report', $this->request->all());

        $template = view('report.comment')
            ->with([
                'title'          => 'Comment Summary',
                'users'          => $users,
                'range'          => $this->request->get('range'),
                'is_super_admin' => $this->isSuperAdmin(),
            ]);
        return $template;
    }

    /**
     * To show the register in the given time interval.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory Registration Summary view
     */
    public function showRegistrationReport()
    {
        $this->filter = $this->authUser() ? $this->request->get('filter', 'all') : 'expert';

        if ($this->dateValidator()->fails()) {
            Noty::warn('The input parameter is wrong');
            return redirect()->back();
        }

        $users = $this->report_repo->getRegistrationReport($this->filter, $this->request->all(), $this->page, $this->per_page);

        Log::info('Search registration report', $this->request->all());

        $template = view('report.registration')
            ->with([
                'title'          => 'Registration Summary',
                'users'          => $users,
                'range'          => $this->request->get('range'),
                'is_super_admin' => $this->isSuperAdmin(),
            ]);
        return $template;
    }

    public function showEventReport($event_id = null)
    {
        if (is_null($event_id)) {
            $event_id = $this->event_repo->getDefaultEvent();
        }

        $dstart  = $this->request->get('dstart') ? $this->request->get('dstart') : EventEnum::AIT_START_DATE;
        $dend    = $this->request->get('dend') ? $this->request->get('dend') : Carbon::now()->toDateString();

        $view     = 'report.event.event-list';

        $event_list       = $this->event_repo->getEvents();

        $join_event_users = $this->event_report_repo->getEventReport($event_id, $this->request->all(), $this->page, $this->per_page);

        $log_data = ['event_id' => $event_id] + $this->request->all();
        Log::info('Search event report', $log_data);

        $begin_number = $join_event_users->total() - (($this->page -1) * $this->per_page);

        $admins = $this->adminer_repo->all();

        $template = view($view)
            ->with([
                'title'            => $event_list[$event_id]['orig'] . ' Summary',
                'event_short_name' => $event_list[$event_id]['short'],
                'event_users'      => $join_event_users,
                'event_list'       => $event_list,
                'event_id'         => $event_id,
                'is_super_admin'   => $this->isSuperAdmin(),
                'begin_number'     => $begin_number,
                'admins'           => $admins,
                'dstart'           => $dstart,
                'dend'             => $dend
            ]);
        return $template;
    }

    public function updateEventMemo()
    {
        if ($this->event_repo->updateEventMemo($this->request->get('id'), $this->request->all())) {
            $result['status'] = 'success';
        } else {
            $result['status'] = 'fail';
        }

        $log_data = $this->request->all() + ['application_id' => $this->request->get('id')];
        Log:info('Edit event application memo', $log_data);

        return json_encode($result);
    }

    public function approveEventUser()
    {
        $user_id  = $this->request->get('user_id');
        $event_id = $this->request->get('event_id');

        $user = $this->user_repo->find($user_id);

        if (empty($user)) {
            return response('', Response::HTTP_NOT_FOUND);
        }

        /* @var QuestionnaireApiInterface $event_api*/
        $event_api = app()->make(QuestionnaireApiInterface::class);
        $response = $event_api->sendNotificationMail($user);

        if ($response->isOk()) {
            $this->event_repo->approveEventUser($user_id, $event_id);
        }

        $log_data = [
            'user_id'  => $user_id,
            'event_id' => $event_id
        ];
        Log::info('Approve event application', $log_data);

        return $response;
    }

    public function showQuestionnaire()
    {
        if ($this->request->get('event')) {
            $event_id = $this->request->get('event');
        } else {
            $event_id = $this->event_repo->getDefaultEvent();
        }

        $dstart  = $this->request->get('dstart') ? $this->request->get('dstart') : EventEnum::AIT_START_DATE;
        $dend    = $this->request->get('dend') ? $this->request->get('dend') : Carbon::now()->toDateString();

        $event_list     = $this->event_repo->getEvents();
        $approve_event_users = $this->event_report_repo->getQuestionnaireReport($event_id, $this->request->all(), $this->page, $this->per_page);

        $log_data = ['event_id' => $event_id] + $this->request->all();
        Log::info('Search questionnaire report', $log_data);

        $view = $this->questionnaire_repo->getView($event_id);

        $admins = $this->adminer_repo->all();

        $template = view($view)
            ->with([
                'title'               => $event_list[$event_id]['orig'],
                'event_short_name'    => $event_list[$event_id]['short'],
                'event_list'          => $event_list,
                'event_id'            => $event_id,
                'approve_event_users' => $approve_event_users,
                'is_super_admin'      => $this->isSuperAdmin(),
                'admins'              => $admins,
                'dstart'              => $dstart,
                'dend'                => $dend
            ]);
        return $template;
    }

    public function showUserQuestionnaire()
    {
        $questionnaire = $this->questionnaire_repo->find($this->request->get('questionnaire_id'));
        $questionnaire_column = $this->questionnaire_repo->getQuestionnaireColumn($questionnaire->subject_id);
        $questionnaire_items  = json_decode($questionnaire->detail, true);
        $template = view('report.event.event-questionnaire')
            ->with([
                'questionnaire_column' => $questionnaire_column,
                'questionnaire_items'  => $questionnaire_items
            ]);
        return $template;
    }

    public function showMemberMatchingReport()
    {
        if ($this->dateValidator()->fails()) {
            Noty::warn('The input parameter is wrong');
            return redirect()->back();
        }

        $input = $this->request->all();

        if ($this->request->get('range')) {
            $input['dstart']    = Carbon::parse($this->request->get('range') - 1 . ' days ago')->toDateString();
        }

        if (empty($this->request->get('range')) && empty($this->request->get('dstart'))) {
            $input['dstart']    = Carbon::parse('13 days ago')->toDateString();
        }

        if (empty($input['dend'])) {
            $input['dend']      = Carbon::now()->toDateString();
        }

        $members = $this->report_repo->getMemberMatchingReport($input, $this->page, $this->per_page);

        return view('report.member-match.list')
            ->with([
                'members' => $members,
                'input'   => $input,
            ]);
    }

    public function showMatchingDate()
    {
        $input = $this->request->all();

        $result = $this->report_repo->getDetailMatchingDataByUser($input['user_id'], $input['dstart'], $input['dend']);

        return view('report.member-match.member-match-detail')
            ->with([
                'recommend_profile'  => $result['recommend']['profile'],
                'recommend_project'  => $result['recommend']['project'],
                'recommend_solution' => $result['recommend']['solution'],
                'referrals_profile'  => $result['referrals']['profile'],
                'referrals_project'  => $result['referrals']['project'],
                'referrals_solution' => $result['referrals']['solution'],
            ]);
    }
}
