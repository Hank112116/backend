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

        if ($filter === 'expert') {
            $users = $this->user_repo->filterExpertsWithToBeExperts($users);
        }
        if ($filter === 'creator') {
            $users = $this->user_repo->filterCreatorWithoutToBeExperts($users);
        }

        $users = $this->user_repo->byCollectionPage($users, $page, $per_page);
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

        // filter complete & incomplete
        if (!empty($input['complete'])) {
            if ($input['complete'] == true) {
                $join_event_users = $join_event_users->filter(function (EventApplication $item) {
                    if ($item->isFinished()) {
                        return $item;
                    }
                });
            } elseif ($input['complete'] == false) {
                $join_event_users = $join_event_users->filter(function (EventApplication $item) {
                    if (!$item->isFinished()) {
                        return $item;
                    }
                });
            }
        }

        if (!empty($input['approve'])) {
            if ($input['approve'] === 'approved') {
                $join_event_users = $join_event_users->filter(function (EventApplication $item) {
                    if ($item->isSelected()) {
                        return $item;
                    }
                });
            } elseif ($input['approve'] === 'no-approve') {
                $join_event_users = $join_event_users->filter(function (EventApplication $item) {
                    if (!$item->isSelected()) {
                        return $item;
                    }
                });
            }
        }

        if (!empty($input['email'])) {
            $email = trim($input['email']);
            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($email) {
                if (Str::equals($item->email, $email)) {
                    return $item;
                }
            });
        }

        if (!empty($input['user_name'])) {
            $user_name = $input['user_name'];
            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($user_name) {
                if (Str::contains($item->textFullName(), $user_name)) {
                    return $item;
                }
            });
        }

        // filter user role
        if (!empty($input['role']) && $input['role'] != 'all') {
            if ($input['role'] === 'expert') {
                $join_event_users = $join_event_users->filter(function (EventApplication $item) {
                    if ($item->user->isExpert()) {
                        return $item;
                    }
                });
            } elseif ($input['role'] === 'creator') {
                $join_event_users = $join_event_users->filter(function (EventApplication $item) {
                    if ($item->user->isCreator()) {
                        return $item;
                    }
                });
            }
        }

        // filter user id
        if (!empty($input['user_id'])) {
            $user_id = trim($input['user_id']);
            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($user_id) {
                if ($item->user->user_id == $user_id) {
                    return $item;
                }
            });
        }

        // filter entered at time
        if (!empty($input['entered_at_start'])) {
            $entered_at_start =  $input['entered_at_start'];
            if (!empty($input['entered_at_end'])) {
                $entered_at_end = $input['entered_at_end'];
            } else {
                $entered_at_end = Carbon::now()->toDateString();
            }

            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($entered_at_start, $entered_at_end) {
                $entered_at = Carbon::parse($item->entered_at)->toDateString();
                if ($entered_at <= $entered_at_end && $entered_at >= $entered_at_start) {
                    return $item;
                }
            });
        }

        // filter applied at time
        if (!empty($input['applied_at_start'])) {
            $applied_at_start =  $input['applied_at_start'];
            if (!empty($input['applied_at_end'])) {
                $applied_at_end = $input['applied_at_end'];
            } else {
                $applied_at_end = Carbon::now()->toDateString();
            }
            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($applied_at_start, $applied_at_end) {
                $applied_at = Carbon::parse($item->applied_at)->toDateString();
                if ($applied_at <= $applied_at_end && $applied_at >= $applied_at_start) {
                    return $item;
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
                    if (Str::contains($item->questionnaire->user->textFullName(), $user_name)) {
                        return $item;
                    }
                } else {
                    if (Str::contains($item->user->textFullName(), $user_name)) {
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
            'meetup_sz_selected'        => 0,
            'meetup_sz_considering'     => 0,
            'meetup_sz_rejected'        => 0,
            'meetup_osaka_selected'     => 0,
            'meetup_osaka_considering'  => 0,
            'meetup_osaka_rejected'     => 0,
        ];

        // filter entered at time
        if (!empty($input['entered_at_start'])) {
            $entered_at_start =  $input['entered_at_start'];
            if (!empty($input['entered_at_end'])) {
                $entered_at_end = $input['entered_at_end'];
            } else {
                $entered_at_end = Carbon::now()->toDateString();
            }

            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($entered_at_start, $entered_at_end) {
                $entered_at = Carbon::parse($item->entered_at)->toDateString();
                if ($entered_at <= $entered_at_end && $entered_at >= $entered_at_start) {
                    return $item;
                }
            });
        }

        /* @var EventApplication $event_user */
        foreach ($join_event_users as $event_user) {
            // tour | meetup
            if ($event_user->isTour()) {
                if ($event_user->isDropped()) {
                    $summary['ait_dropped'] = $summary['ait_dropped'] + 1;
                } else {
                    $summary['ait_applied'] = $summary['ait_applied'] + 1;
                }

                if ($event_user->isSelected()) {
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
                                    case 'selected':
                                        $summary['meetup_sz_selected'] = $summary['meetup_sz_selected'] + 1;
                                        break;
                                    case 'considering':
                                        $summary['meetup_sz_considering'] = $summary['meetup_sz_considering'] + 1;
                                        break;
                                    case 'rejected':
                                        $summary['meetup_sz_rejected'] = $summary['meetup_sz_rejected'] + 1;
                                        break;
                                }
                            } elseif ($trip_participation == 'osaka') {
                                $summary['meetup_osaka_applied'] = $summary['meetup_osaka_applied'] + 1;

                                switch ($event_user->getInternalSetStatus()) {
                                    case 'selected':
                                        $summary['meetup_osaka_selected'] = $summary['meetup_osaka_selected'] + 1;
                                        break;
                                    case 'considering':
                                        $summary['meetup_osaka_considering'] = $summary['meetup_osaka_considering'] + 1;
                                        break;
                                    case 'rejected':
                                        $summary['meetup_osaka_rejected'] = $summary['meetup_osaka_rejected'] + 1;
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
