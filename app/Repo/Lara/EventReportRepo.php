<?php namespace Backend\Repo\Lara;

use Backend\Enums\EventEnum;
use Backend\Model\Eloquent\EventApplication;
use Backend\Model\Eloquent\User;
use Backend\Repo\RepoInterfaces\EventApplicationInterface;
use Backend\Repo\RepoInterfaces\EventQuestionnaireInterface;
use Backend\Repo\RepoInterfaces\EventReportInterface;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class EventReportRepo implements EventReportInterface
{
    private $event_repo;
    private $questionnaire_repo;

    public function __construct(
        EventApplicationInterface $event_repo,
        EventQuestionnaireInterface $questionnaire_repo
    ) {
        $this->event_repo          = $event_repo;
        $this->questionnaire_repo  = $questionnaire_repo;
    }

    public function getEventReport($event_id, $input, $page, $per_page)
    {
        /* @var Collection $join_event_users */
        $join_event_users = $this->event_repo->findByEventId($event_id);

        $summary = $this->getEventSummary($join_event_users, $input);

        if (!empty($input['dstart'])) {
            $dstart =  $input['dstart'];
            if (!empty($input['dend'])) {
                $dend = $input['dend'];
            } else {
                $dend = Carbon::now()->toDateString();
            }

            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($dstart, $dend) {
                if ($item->applied_at) {
                    $applied_at = Carbon::parse($item->applied_at)->toDateString();
                    if ($applied_at <= $dend && $applied_at >= $dstart) {
                        return $item;
                    }
                } else {
                    $entered_at = Carbon::parse($item->entered_at)->toDateString();
                    if ($entered_at <= $dend && $entered_at >= $dstart) {
                        return $item;
                    }
                }
            });
        }

        // filter user role
        if (!empty($input['role']) && $input['role'] != 'all') {
            $role = $input['role'];
            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($role) {
                switch ($role) {
                    case User::TYPE_CREATOR:
                        if ($item->user->isBasicCreator()) {
                            return $item;
                        }
                        break;
                    case User::TYPE_PREMIUM_CREATOR:
                        if ($item->user->isPremiumCreator()) {
                            return $item;
                        }
                        break;
                    case User::TYPE_EXPERT:
                        if ($item->user->isBasicExpert()) {
                            return $item;
                        }
                        break;
                    case User::TYPE_PREMIUM_EXPERT:
                        if ($item->user->isPremiumExpert()) {
                            return $item;
                        }
                        break;
                    case User::TYPE_TO_BE_EXPERT:
                        if ($item->user->isToBeExpert()) {
                            return $item;
                        }
                        break;
                    case User::TYPE_PM:
                        if ($item->user->isHWTrekPM()) {
                            return $item;
                        }
                        break;
                }
            });
        }

        if (!empty($input['project'])) {
            $project = $input['project'];

            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($project) {
                if ($item->project) {
                    if (is_numeric($project)) {
                        if ($item->project_id == $project) {
                            return $item;
                        }
                    } else {
                        if (stristr($item->project->project_title, $project)) {
                            return $item;
                        }
                    }
                }
            });
        }

        if (!empty($input['user'])) {
            $user = $input['user'];

            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($user) {
                if ($item->user) {
                    if (is_numeric($user)) {
                        if ($item->user_id == $user) {
                            return $item;
                        }
                    } else {
                        if (stristr($item->textFullName(), $user)
                            or stristr($item->user->textFullName(), $user)
                        ) {
                            return $item;
                        }
                    }
                }
            });
        }

        if (!empty($input['assigned_pm'])) {
            $assigned_pm = explode(',', $input['assigned_pm']);
            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($assigned_pm) {
                foreach ($assigned_pm as $row) {
                    if (stristr($item->getFollowPM(), $row)) {
                        return $item;
                    }
                }
                if ($item->project) {
                    /* @var Collection $manager_names*/
                    $manager_names =  $item->project->getHubManagerNames();
                    if ($manager_names) {
                        foreach ($manager_names as $name) {
                            foreach ($assigned_pm as $pm) {
                                if (stristr($name, $pm)) {
                                    return $item;
                                }
                            }
                        }
                    }
                }
            });
        }

        if (!empty($input['company'])) {
            $company = trim($input['company']);
            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($company) {
                if (stristr($item->company, $company)) {
                    return $item;
                }
            });
        }

        if (!empty($input['email'])) {
            $email = trim($input['email']);
            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($email) {
                if (stristr($item->email, $email)) {
                    return $item;
                }
            });
        }

        if (!empty($input['ticket'])) {
            $ticket = trim($input['ticket']);

            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($ticket) {
                switch ($ticket) {
                    case 'tour':
                        if ($item->isTour()) {
                            return $item;
                        }
                        break;
                    case 'meetup-sz':
                        if (!$item->isTour() and in_array('shenzhen', $item->getTripParticipation())) {
                            return $item;
                        }
                        break;
                    case 'meetup-osaka':
                        if (!$item->isTour() and in_array('osaka', $item->getTripParticipation())) {
                            return $item;
                        }
                        break;
                    case 'meetup-sh':
                        if (!$item->isTour() and in_array('shanghai', $item->getTripParticipation())) {
                            return $item;
                        }
                        break;
                    case 'meetup':
                        if (!$item->isTour()) {
                            return $item;
                        }
                        break;
                }
            });
        }

        if (!empty($input['status'])) {
            $status = trim($input['status']);

            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($status) {
                switch ($status) {
                    case 'applied':
                        if ($item->isFinished()) {
                            return $item;
                        }
                        break;
                    case 'dropped':
                        if ($item->isDropped()) {
                            return $item;
                        }
                        break;
                }
            });
        }

        if (!empty($input['internal_status'])) {
            $status = trim($input['internal_status']);

            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($status) {
                switch ($status) {
                    case 'form-sent':
                        if ($item->isFormSent()) {
                            return $item;
                        }
                        break;
                    case 'selected':
                        if ($item->isInternalSelected()) {
                            return $item;
                        }
                        break;
                    case 'considering':
                        if ($item->isInternalConsidering()) {
                            return $item;
                        }
                        break;
                    case 'rejected':
                        if ($item->isInternalRejected()) {
                            return $item;
                        }
                        break;
                    case 'premium':
                        if ($item->isInternalPremium()) {
                            return $item;
                        }
                        break;
                    case 'expert':
                        if ($item->isInternalExpert()) {
                            return $item;
                        }
                        break;
                    case 'sent':
                        if ($item->isAlreadySendMail()) {
                            return $item;
                        }
                        break;
                }
            });
        }

        $join_event_users = $this->event_repo->byCollectionPage($join_event_users, $page, $per_page);
        $join_event_users = $this->appendStatistics($join_event_users, $summary);
        return $join_event_users;
    }

    public function getQuestionnaireReport($event_id, $input, $page, $per_page)
    {
        /* @var Collection $approve_event_users */
        $approve_event_users = $this->event_repo->findApproveEventUsers($event_id);

        if ($approve_event_users) {
            foreach ($approve_event_users as $index => $user) {
                $questionnaire = $this->questionnaire_repo->findByApproveUser($user);
                $approve_event_users[$index]->questionnaire = $questionnaire;
            }
        }

        if (!empty($input['participation'])) {
            $participation = trim($input['participation']);
            $approve_event_users = $approve_event_users->filter(function ($item) use ($participation) {
                if ($item->questionnaire) {
                    if (in_array($participation, $item->questionnaire->trip_participation)) {
                        return $item;
                    }
                }
            });
        }

        if (!empty($input['user_name'])) {
            $user_name = $input['user_name'];
            $approve_event_users = $approve_event_users->filter(function ($item) use ($user_name) {
                if ($item->questionnaire) {
                    if (stristr($item->questionnaire->user->textFullName(), $user_name)) {
                        return $item;
                    }
                } else {
                    if (stristr($item->user->textFullName(), $user_name)) {
                        return $item;
                    }
                }
            });
        }

        if (!empty($input['user_id'])) {
            $user_id = trim($input['user_id']);
            $approve_event_users = $approve_event_users->filter(function ($item) use ($user_id) {
                if ($item->questionnaire) {
                    if ($item->questionnaire->user_id == $user_id) {
                        return $item;
                    }
                } else {
                    if ($item->user_id == $user_id) {
                        return $item;
                    }
                }
            });
        }

        if (!empty($input['form_status'])) {
            $form_status = trim($input['form_status']);
            $approve_event_users = $approve_event_users->filter(function ($item) use ($form_status) {
                if ($item->questionnaire) {
                    if ($item->questionnaire->form_status == $form_status) {
                        return $item;
                    }
                }
            });
        }

        if (!empty($input['internal_form_status'])) {
            $internal_form_status = trim($input['internal_form_status']);
            $approve_event_users = $approve_event_users->filter(function (EventApplication $item) use ($internal_form_status) {
                if ($item->getInternalSetFormStatus() === $internal_form_status) {
                    return $item;
                }
            });
        }

        if (!empty($input['attendees'])) {
            $attendees = trim($input['attendees']);
            $approve_event_users = $approve_event_users->filter(function ($item) use ($attendees) {
                if ($item->questionnaire) {
                    if ($item->questionnaire->trip_participation) {
                        if (in_array($attendees, $item->questionnaire->trip_participation)) {
                            return $item;
                        }
                    }
                }
            });
        }

        if (!empty($input['project'])) {
            $project = $input['project'];

            $approve_event_users = $approve_event_users->filter(function (EventApplication $item) use ($project) {
                if ($item->project) {
                    if (is_numeric($project)) {
                        if ($item->project_id == $project) {
                            return $item;
                        }
                    } else {
                        if (stristr($item->project->project_title, $project)) {
                            return $item;
                        }
                    }
                }
            });
        }

        if (!empty($input['user'])) {
            $user = $input['user'];

            $approve_event_users = $approve_event_users->filter(function (EventApplication $item) use ($user) {
                if ($item->user) {
                    if (is_numeric($user)) {
                        if ($item->user_id == $user) {
                            return $item;
                        }
                    } else {
                        if ($item->questionnaire) {
                            if (stristr($item->questionnaire->attendee_name, $user)) {
                                return $item;
                            }
                        }
                    }
                }
            });
        }

        if (!empty($input['assigned_pm'])) {
            $assigned_pm = explode(',', $input['assigned_pm']);
            $approve_event_users = $approve_event_users->filter(function (EventApplication $item) use ($assigned_pm) {
                foreach ($assigned_pm as $row) {
                    if (stristr($item->getFollowPM(), $row)) {
                        return $item;
                    }
                }
                if ($item->project) {
                    /* @var Collection $manager_names*/
                    $manager_names =  $item->project->getHubManagerNames();
                    if ($manager_names) {
                        foreach ($manager_names as $name) {
                            foreach ($assigned_pm as $pm) {
                                if (stristr($name, $pm)) {
                                    return $item;
                                }
                            }
                        }
                    }
                }
            });
        }

        if (!empty($input['company'])) {
            $company = trim($input['company']);
            $approve_event_users = $approve_event_users->filter(function (EventApplication $item) use ($company) {
                if ($item->questionnaire) {
                    if (stristr($item->questionnaire->company_name, $company)) {
                        return $item;
                    }
                }
            });
        }

        if (!empty($input['email'])) {
            $email = trim($input['email']);
            $approve_event_users = $approve_event_users->filter(function (EventApplication $item) use ($email) {
                if ($item->questionnaire) {
                    if (stristr($item->questionnaire->email, $email)) {
                        return $item;
                    }
                }
            });
        }

        $approve_event_users = $this->event_repo->byCollectionPage($approve_event_users, $page, $per_page);
        switch ($event_id) {
            case EventEnum::TYPE_AIT_2016_Q1:
                $statistics          = $this->getQuestionnaireStatistics($approve_event_users);
                $approve_event_users = $this->appendStatistics($approve_event_users, $statistics);
                break;
            case EventEnum::TYPE_AIT_2016_Q4:
            case EventEnum::TYPE_AIT_2017_Q2:
                $join_event_users   = $this->event_repo->findByEventId($event_id);
                $join_event_summary = $this->getEventSummary($join_event_users, $input);
                $summary_approve_event_users = $this->event_repo->findApproveEventUsers($event_id);
                $approve_event_summary       = $this->getQuestionnaireSummary($summary_approve_event_users, $input);

                $approve_event_users = $this->appendStatistics($approve_event_users, $join_event_summary);
                $approve_event_users = $this->appendStatistics($approve_event_users, $approve_event_summary);
                break;
        }
        return $approve_event_users;
    }

    private function getEventSummary(Collection $join_event_users, $input)
    {
        $summary = [
            'ait_applied'               => 0,
            'meetup_sz_applied'         => 0,
            'meetup_osaka_applied'      => 0,
            'meetup_sh_applied'         => 0,
            'ait_dropped'               => 0,
            'meetup_dropped'            => 0,
            'ait_form_sent'             => 0,
            'ait_selected'              => 0,
            'ait_considering'           => 0,
            'ait_rejected'              => 0,
            'meetup_sz_sent'            => 0,
            'meetup_sz_rejected'        => 0,
            'meetup_sz_premium'         => 0,
            'meetup_sz_expert'          => 0,
            'meetup_osaka_sent'         => 0,
            'meetup_osaka_rejected'     => 0,
            'meetup_osaka_premium'      => 0,
            'meetup_osaka_expert'       => 0,
            'meetup_sh_sent'            => 0,
            'meetup_sh_rejected'        => 0,
            'meetup_sh_premium'         => 0,
            'meetup_sh_expert'          => 0,
        ];

        // filter entered at time
        if (!empty($input['dstart'])) {
            $dstart =  $input['dstart'];
            if (!empty($input['dend'])) {
                $dend = $input['dend'];
            } else {
                $dend = Carbon::now()->toDateString();
            }

            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($dstart, $dend) {
                if ($item->applied_at) {
                    $applied_at = Carbon::parse($item->applied_at)->toDateString();
                    if ($applied_at <= $dend && $applied_at >= $dstart) {
                        return $item;
                    }
                } else {
                    $entered_at = Carbon::parse($item->entered_at)->toDateString();
                    if ($entered_at <= $dend && $entered_at >= $dstart) {
                        return $item;
                    }
                }
            });
        }

        /* @var EventApplication $event_user */
        foreach ($join_event_users as $event_user) {
            if (!$event_user->user) {
                continue;
            }
            // tour | meetup
            if ($event_user->isTour()) {
                if ($event_user->isDropped()) {
                    $summary['ait_dropped'] = $summary['ait_dropped'] + 1;
                } else {
                    $summary['ait_applied'] = $summary['ait_applied'] + 1;
                }

                if ($event_user->isFormSent()) {
                    $summary['ait_form_sent'] = $summary['ait_form_sent'] + 1;
                }

                switch ($event_user->getInternalSetStatus()) {
                    case 'selected':
                        $summary['ait_selected'] = $summary['ait_selected'] + 1;
                        break;
                    case 'considering':
                        $summary['ait_considering'] = $summary['ait_considering'] + 1;
                        break;
                    case 'rejected':
                        $summary['ait_rejected'] = $summary['ait_rejected'] + 1;
                        break;
                }
            } else {
                if ($event_user->isDropped()) {
                    $summary['meetup_dropped'] = $summary['meetup_dropped'] + 1;
                } else {
                    if ($event_user->getTripParticipation()) {
                        foreach ($event_user->getTripParticipation() as $trip_participation) {
                            if ($trip_participation == 'shenzhen') {
                                $summary['meetup_sz_applied'] = $summary['meetup_sz_applied'] + 1;
                                if ($event_user->isAlreadySendSZMail()) {
                                    $summary['meetup_sz_sent'] = $summary['meetup_sz_sent'] + 1;
                                    if ($event_user->hasGuestJoin()) {
                                        $summary['meetup_sz_sent'] = $summary['meetup_sz_sent'] + 1;
                                    }
                                }
                                switch ($event_user->getInternalSetStatus()) {
                                    case 'rejected':
                                        $summary['meetup_sz_rejected'] = $summary['meetup_sz_rejected'] + 1;
                                        break;
                                    case 'premium':
                                        $summary['meetup_sz_premium'] = $summary['meetup_sz_premium'] + 1;
                                        break;
                                    case 'expert':
                                        $summary['meetup_sz_expert'] = $summary['meetup_sz_expert'] + 1;
                                        break;
                                }
                            } elseif ($trip_participation == 'osaka') {
                                $summary['meetup_osaka_applied'] = $summary['meetup_osaka_applied'] + 1;
                                if ($event_user->isAlreadySendOsakaMail()) {
                                    $summary['meetup_osaka_sent'] = $summary['meetup_osaka_sent'] + 1;
                                    if ($event_user->hasGuestJoin()) {
                                        $summary['meetup_osaka_sent'] = $summary['meetup_osaka_sent'] + 1;
                                    }
                                }
                                switch ($event_user->getInternalSetStatus()) {
                                    case 'rejected':
                                        $summary['meetup_osaka_rejected'] = $summary['meetup_osaka_rejected'] + 1;
                                        break;
                                    case 'premium':
                                        $summary['meetup_osaka_premium'] = $summary['meetup_osaka_premium'] + 1;
                                        break;
                                    case 'expert':
                                        $summary['meetup_osaka_expert'] = $summary['meetup_osaka_expert'] + 1;
                                        break;
                                }
                            } elseif ($trip_participation == 'shanghai') {
                                $summary['meetup_sh_applied'] = $summary['meetup_sh_applied'] + 1;
                                if ($event_user->isAlreadySendSHMail()) {
                                    $summary['meetup_sh_sent'] = $summary['meetup_sh_sent'] + 1;
                                    if ($event_user->hasGuestJoin()) {
                                        $summary['meetup_sh_sent'] = $summary['meetup_sh_sent'] + 1;
                                    }
                                }
                                switch ($event_user->getInternalSetStatus()) {
                                    case 'rejected':
                                        $summary['meetup_sh_rejected'] = $summary['meetup_sh_rejected'] + 1;
                                        break;
                                    case 'premium':
                                        $summary['meetup_sh_premium'] = $summary['meetup_sh_premium'] + 1;
                                        break;
                                    case 'expert':
                                        $summary['meetup_sh_expert'] = $summary['meetup_sh_expert'] + 1;
                                        break;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $summary;
    }

    private function getQuestionnaireSummary(Collection $approve_event_users, $input)
    {
        $summary = [
            'ait_form_rejected'         => 0,
            'ait_form_ongoing'          => 0,
            'ait_form_completed'        => 0,
            'ait_dinner'                => 0,
            'ait_sz'                    => 0,
            'ait_sh'                    => 0,
            'ait_kyoto'                 => 0,
            'ait_osaka'                 => 0,
            'ait_guest_dinner'          => 0,
            'ait_guest_sz'              => 0,
            'ait_guest_sh'              => 0,
            'ait_guest_kyoto'           => 0,
            'ait_guest_osaka'           => 0,
            'ait_form_internal_decline' => 0,
        ];

        // filter entered at time
        if (!empty($input['dstart'])) {
            $dstart =  $input['dstart'];
            if (!empty($input['dend'])) {
                $dend = $input['dend'];
            } else {
                $dend = Carbon::now()->toDateString();
            }

            $approve_event_users = $approve_event_users->filter(function (EventApplication $item) use ($dstart, $dend) {
                if ($item->approved_at) {
                    $approved_at = Carbon::parse($item->approved_at)->toDateString();
                    if ($approved_at <= $dend && $approved_at >= $dstart) {
                        return $item;
                    }
                }
            });
        }

        /* @var EventApplication $approve_event_user */
        foreach ($approve_event_users as $approve_event_user) {
            if ($approve_event_user->getInternalSetFormStatus() === 'Decline') {
                $summary['ait_form_internal_decline'] = $summary['ait_form_internal_decline'] + 1;
            }

            $questionnaire = $this->questionnaire_repo->findByApproveUser($approve_event_user);
            if (empty($questionnaire)) {
                continue;
            }
            switch ($questionnaire->form_status) {
                case 'Decline':
                    $summary['ait_form_rejected'] = $summary['ait_form_rejected'] + 1;
                    break;
                case 'Ongoing':
                    $summary['ait_form_ongoing'] = $summary['ait_form_ongoing'] + 1;
                    break;
                case 'Completed':
                    $summary['ait_form_completed'] = $summary['ait_form_completed'] + 1;
                    break;
            }

            if ($questionnaire->form_status === 'Decline'
                or ($approve_event_user->getInternalSetFormStatus() === 'Decline'
                    and $questionnaire->form_status !== 'Completed') ) {
                continue;
            }
            
            if ($questionnaire->trip_participation) {
                foreach ($questionnaire->trip_participation as $trip) {
                    if ($trip == 'dinner') {
                        $summary['ait_dinner'] = $summary['ait_dinner'] + 1;

                        if ($questionnaire->guest_info
                            or $questionnaire->guest_attendee_name
                            or $questionnaire->guest_job_title
                            or $questionnaire->guest_email
                        ) {
                            $summary['ait_guest_dinner'] = $summary['ait_guest_dinner'] + 1;
                        }
                    }

                    if ($trip == 'shenzhen') {
                        $summary['ait_sz'] = $summary['ait_sz'] + 1;

                        if ($questionnaire->guest_info
                            or $questionnaire->guest_attendee_name
                            or $questionnaire->guest_job_title
                            or $questionnaire->guest_email
                        ) {
                            $summary['ait_guest_sz'] = $summary['ait_guest_sz'] + 1;
                        }
                    }

                    if ($trip == 'kyoto') {
                        $summary['ait_kyoto'] = $summary['ait_kyoto'] + 1;

                        if ($questionnaire->guest_info
                            or $questionnaire->guest_attendee_name
                            or $questionnaire->guest_job_title
                            or $questionnaire->guest_email
                        ) {
                            $summary['ait_guest_kyoto'] = $summary['ait_guest_kyoto'] + 1;
                        }
                    }

                    if ($trip == 'osaka') {
                        $summary['ait_osaka'] = $summary['ait_osaka'] + 1;

                        if ($questionnaire->guest_info
                            or $questionnaire->guest_attendee_name
                            or $questionnaire->guest_job_title
                            or $questionnaire->guest_email
                        ) {
                            $summary['ait_guest_osaka'] = $summary['ait_guest_osaka'] + 1;
                        }
                    }

                    if ($trip == 'shanghai') {
                        $summary['ait_sh'] = $summary['ait_sh'] + 1;

                        if ($questionnaire->guest_info
                            or $questionnaire->guest_attendee_name
                            or $questionnaire->guest_job_title
                            or $questionnaire->guest_email
                        ) {
                            $summary['ait_guest_sh'] = $summary['ait_guest_sh'] + 1;
                        }
                    }
                }
            }
        }
        return $summary;
    }

    private function getQuestionnaireStatistics($approve_event_users)
    {
        $result = [];
        $result['dinner_count'] = $approve_event_users->filter(function ($item) {
            if ($item->questionnaire) {
                if ($item->questionnaire->attend_to_april_dinner == '1') {
                    return $item;
                }
            }
        })->count();

        $result['prototype_count'] = $approve_event_users->filter(function ($item) {
            if ($item->questionnaire) {
                if ($item->questionnaire->bring_prototype == '1') {
                    return $item;
                }
            }
        })->count();

        $result['join_count'] = $approve_event_users->filter(function ($item) {
            if ($item->questionnaire) {
                if ($item->questionnaire->other_member_to_join == '1') {
                    return $item;
                }
            }
        })->count();

        $result['wechat_count'] = $approve_event_users->filter(function ($item) {
            if ($item->questionnaire) {
                if ($item->questionnaire->wechat_account) {
                    return $item;
                }
            }
        })->count();

        $result['material_count'] = $approve_event_users->filter(function ($item) {
            if ($item->questionnaire) {
                if ($item->questionnaire->forward_material == '1') {
                    return $item;
                }
            }
        })->count();

        $result['shenzhen_count'] = $approve_event_users->filter(function ($item) {
            if ($item->questionnaire) {
                if (in_array('shenzhen', $item->questionnaire->trip_participation)) {
                    return $item;
                }
            }
        })->count();

        $result['beijing_count'] = $approve_event_users->filter(function ($item) {
            if ($item->questionnaire) {
                if (in_array('beijing', $item->questionnaire->trip_participation)) {
                    return $item;
                }
            }
        })->count();

        $result['taipei_count'] = $approve_event_users->filter(function ($item) {
            if ($item->questionnaire) {
                if (in_array('taipei', $item->questionnaire->trip_participation)) {
                    return $item;
                }
            }
        })->count();

        return $result;
    }

    private function appendStatistics($object, $statistics)
    {
        foreach ($statistics as $key => $row) {
            $object->$key = $row;
        }
        return $object;
    }
}
