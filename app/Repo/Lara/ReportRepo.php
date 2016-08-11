<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\User;
use Backend\Model\Eloquent\ProposeSolution;
use Backend\Model\Eloquent\GroupMemberApplicant;
use Backend\Model\Eloquent\ProjectMailExpert;
use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Repo\RepoInterfaces\ReportInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoInterfaces\EventApplicationInterface;
use Backend\Repo\RepoInterfaces\EventQuestionnaireInterface;
use Backend\Repo\RepoTrait\PaginateTrait;
use Backend\Model\Eloquent\EventApplication;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Carbon;
use UrlFilter;

class ReportRepo implements ReportInterface
{
    use PaginateTrait;

    private $user_repo;
    private $project_repo;
    private $admin_repo;
    private $event_repo;
    private $questionnaire_repo;

    public function __construct(
        UserInterface               $user_repo,
        ProjectInterface            $project_repo,
        AdminerInterface            $admin_repo,
        EventApplicationInterface   $even_repo,
        EventQuestionnaireInterface $questionnaire_repo
    ) {
        $this->user_repo          = $user_repo;
        $this->project_repo       = $project_repo;
        $this->admin_repo         = $admin_repo;
        $this->event_repo         = $even_repo;
        $this->questionnaire_repo = $questionnaire_repo;
    }

    private function getTimeInterval($input)
    {
        $dstart = isset($input['dstart']) ? $input['dstart'] : null;
        $dend   = isset($input['dend']) ? $input['dend'] : null;
        $range  = isset($input['range']) ? $input['range'] : 7;

        if ($dstart === null || $dend === null) {
            $dstart = Carbon::parse($range . ' days ago')->toDateString();
            $dend   = Carbon::now()->toDateString();
        }
        return [ $dstart, $dend ];
    }

    public function getCommentReport($filter, $input, $page, $per_page)
    {
        $timeRange = $this->getTimeInterval($input);

        if (isset($input['name']) && $input['name'] != '') {
            $users = $this->user_repo->getCommentCountsByDateByName($timeRange[0], $timeRange[1], $input['name']);
        } elseif (isset($input['id']) && $input['id'] != '') {
            $users = $this->user_repo->getCommentCountsByDateById($timeRange[0], $timeRange[1], $input['id']);
        } else {
            $users = $this->user_repo->getCommentCountsByDate($timeRange[0], $timeRange[1]);
        }

        if ($filter === 'expert') {
            $users = $this->user_repo->filterExpertsWithToBeExperts($users);
        }
        if ($filter === 'creator') {
            $users = $this->user_repo->filterCreatorWithoutToBeExperts($users);
        }
        if ($filter === 'pm') {
            $users = $this->user_repo->filterPM($users);
        }


        $users = $users->sort(function ($user1, $user2) {
            return $user2->commentCount - $user1->commentCount ?: $user2->user_id - $user1->user_id; // First sort by sendCommentCount, and second sort by user_id
        });

        //calculate count of sender;
        $senderCount = $users->filter(
            function (User $user) {
                return $user->commentCount != 0;
            }
        )->count();

        //In order to get comment sum correctly, we should get sum before pagination.
        $commentSum         = $users->sum('commentCount');
        $users              = $this->user_repo->byCollectionPage($users, $page, $per_page);
        $users->commentSum  = $commentSum;
        $users->senderCount = $senderCount;

        return $users;
    }

    public function getRegistrationReport($filter, $input, $page, $per_page)
    {
        $timeRange = $this->getTimeInterval($input);

        $users = $this->user_repo->byDateRange($timeRange[0], $timeRange[1]);

        $summary = $this->getRegistrationSummary($users);

        if ($filter === 'expert') {
            $users = $this->user_repo->filterExpertsWithToBeExperts($users);
        }
        if ($filter === 'creator') {
            $users = $this->user_repo->filterCreatorWithoutToBeExperts($users);
        }

        $users = $this->user_repo->byCollectionPage($users, $page, $per_page);
        $users = $this->appendStatistics($users, $summary);
        return $users;
    }

    public function getProjectReport($input, $page, $per_page)
    {
        $projects = $this->project_repo->byUnionSearch($input, $page, $per_page, true);
        return $projects;
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
            if ($input['role'] === 'expert') {
                $join_event_users = $join_event_users->filter(function (EventApplication $item) {
                    if ($item->user->isExpert() or $item->user->isPendingExpert()) {
                        return $item;
                    }
                });
            } elseif ($input['role'] === 'creator') {
                $join_event_users = $join_event_users->filter(function (EventApplication $item) {
                    if ($item->user->isCreator() and !$item->user->isPendingExpert()) {
                        return $item;
                    }
                });
            }
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

        $statistics     = $this->getQuestionnaireStatistics($approve_event_users);
        $approve_event_users = $this->event_repo->byCollectionPage($approve_event_users, $page, $per_page);
        $approve_event_users = $this->appendStatistics($approve_event_users, $statistics);
        return $approve_event_users;
    }

    private function getRegistrationSummary(Collection $users)
    {
        $result = [];
        $result['not_approve'] = $users->filter(function (User $item) {
            if ($item->isPendingExpert()) {
                return $item;
            }
        })->count();

        $result['email_not_verify'] = $users->filter(function (User $item) {
            if (!$item->isEmailVerify()) {
                return $item;
            }
        })->count();

        $result['account_suspend'] = $users->filter(function (User $item) {
            if (!$item->isActive()) {
                return $item;
            }
        })->count();

        return $result;
    }

    private function getQuestionnaireStatistics(Collection $approve_event_users)
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

    private function getEventSummary(Collection $join_event_users, $input)
    {
        $summary = [
            'ait_applied'               => 0,
            'meetup_sz_applied'         => 0,
            'meetup_osaka_applied'      => 0,
            'ait_dropped'               => 0,
            'meetup_dropped'            => 0,
            'ait_form_sent'             => 0,
            'ait_selected'              => 0,
            'ait_considering'           => 0,
            'ait_rejected'              => 0,
            'meetup_sz_rejected'        => 0,
            'meetup_sz_premium'         => 0,
            'meetup_sz_expert'          => 0,
            'meetup_osaka_rejected'     => 0,
            'meetup_osaka_premium'      => 0,
            'meetup_osaka_expert'       => 0
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
                            }
                        }
                    }
                }
            }
        }
        return $summary;
    }
}
