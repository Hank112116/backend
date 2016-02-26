<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\User;
use Backend\Repo\RepoInterfaces\ReportInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoInterfaces\EventApplicationInterface;
use Backend\Repo\RepoInterfaces\EventQuestionnaireInterface;
use Backend\Repo\RepoTrait\PaginateTrait;
use Backend\Model\Eloquent\EventApplication;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Carbon;

class ReportRepo implements ReportInterface
{
    use PaginateTrait;

    private $user_repo;
    private $event_repo;
    private $questionnaire_repo;

    public function __construct(
        UserInterface               $user_repo,
        EventApplicationInterface   $even_repo,
        EventQuestionnaireInterface $questionnaire_repo
    ) {
        $this->user_repo          = $user_repo;
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
    
    public function getEventReport($event_id, $complete, $input, $page, $per_page)
    {
        /* @var Collection $join_event_users */
        $join_event_users = $this->event_repo->findByEventId($event_id);

        // filter complete & incomplete
        if ($complete) {
            $join_event_users = $join_event_users->filter(function (EventApplication $item) {
                if ($item->isFinished()) {
                    return $item;
                }
            });
        } else {
            $join_event_users = $join_event_users->filter(function (EventApplication $item) {
                if (!$item->isFinished()) {
                    return $item;
                }
            });
        }

        if (isset($input['approve']) && !empty($input['approve'])) {
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

        if (isset($input['email']) && !empty($input['email'])) {
            $email = trim($input['email']);
            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($email) {
                if (Str::equals($item->email, $email)) {
                    return $item;
                }
            });
        }

        if (isset($input['user_name']) && !empty($input['user_name'])) {
            $user_name = $input['user_name'];
            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($user_name) {
                if (Str::contains($item->textFullName(), $user_name)) {
                    return $item;
                }
            });
        }

        // filter user role
        if (isset($input['role']) && $input['role'] != 'all') {
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
        if (isset($input['user_id']) && !empty($input['user_id'])) {
            $user_id = trim($input['user_id']);
            $join_event_users = $join_event_users->filter(function (EventApplication $item) use ($user_id) {
                if ($item->user->user_id == $user_id) {
                    return $item;
                }
            });
        }

        // filter entered at time
        if (isset($input['entered_at_start']) && !empty($input['entered_at_start'])) {
            $entered_at_start =  $input['entered_at_start'];
            if (isset($input['entered_at_end']) && !empty($input['entered_at_end'])) {
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
        if (isset($input['applied_at_start']) && !empty($input['applied_at_start'])) {
            $applied_at_start =  $input['applied_at_start'];
            if (isset($input['applied_at_end']) && !empty($input['applied_at_end'])) {
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

        $statistics       = $this->getEventStatistics($complete, $join_event_users);
        $join_event_users = $this->event_repo->byCollectionPage($join_event_users, $page, $per_page);

        foreach ($statistics as $key => $row) {
            $join_event_users->$key = $row;
        }
        return $join_event_users;
    }

    private function getEventStatistics($complete, $join_event_users)
    {
        $result = [];
        if ($complete) {
            $creator_users = $result['creator_count'] = $join_event_users->filter(function (EventApplication $item) {
                if ($item->user->isCreator()) {
                    return $item;
                }
            });
            $result['creator_count']        = $creator_users->count();
            $result['unique_creator_count'] = $creator_users->unique('user_id')->count();

            $expert_users = $join_event_users->filter(function (EventApplication $item) {
                if ($item->user->isExpert()) {
                    return $item;
                }
            });
            $result['expert_count']        = $expert_users->count();
            $result['unique_expert_count'] = $expert_users->unique('user_id')->count();

            $result['approved_count'] = $join_event_users->filter(function (EventApplication $item) {
                if ($item->isFinished()) {
                    return $item;
                }
            })->count();
        } else {
            $result['complete_count'] = $join_event_users->filter(function (EventApplication $item) {
                if ($item->getCompleteTime()) {
                    return $item;
                }
            })->count();

            $complete_users = $join_event_users->filter(function (EventApplication $item) {
                if ($item->getCompleteTime()) {
                    return $item;
                }
            });

            $result['complete_count']        = $complete_users->count();
            $result['unique_complete_count'] = $complete_users->unique('email')->count();

        }
        return $result;
    }

    public function getQuestionnaireReport($event_id, $input, $page, $per_page)
    {
        $questionnaires =  $this->questionnaire_repo->findByEventId($event_id);

        if (isset($input['participation']) && !empty($input['participation'])) {
            $participation = trim($input['participation']);
            $questionnaires = $questionnaires->filter(function ($item) use ($participation) {
                if (in_array($participation, $item->trip_participation)) {
                    return $item;
                }
            });
        }

        if (isset($input['user_name']) && !empty($input['user_name'])) {
            $user_name = $input['user_name'];
            $questionnaires = $questionnaires->filter(function ($item) use ($user_name) {
                if (Str::contains($item->user->textFullName(), $user_name)) {
                    return $item;
                }
            });
        }

        if (isset($input['user_id']) && !empty($input['user_id'])) {
            $user_id = trim($input['user_id']);
            $questionnaires = $questionnaires->filter(function ($item) use ($user_id) {
                if ($item->user_id == $user_id) {
                    return $item;
                }
            });
        }

        $questionnaires = $this->event_repo->byCollectionPage($questionnaires, $page, $per_page);
        return $questionnaires;
    }

    public function getQuestionnaireStatistics($questionnaires)
    {

        $questionnaires->dinner_count = $questionnaires->filter(function ($item) {
            if ($item->attend_to_april_dinner == 'true') {
                return $item;
            }
        })->count();

        $questionnaires->prototype_count = $questionnaires->filter(function ($item) {
            if ($item->bring_prototype == 'true') {
                return $item;
            }
        })->count();

        $questionnaires->join_count = $questionnaires->filter(function ($item) {
            if ($item->other_member_to_join == 'true') {
                return $item;
            }
        })->count();

        $questionnaires->wechat_count = $questionnaires->filter(function ($item) {
            if ($item->wechat_account == 'true') {
                return $item;
            }
        })->count();

        $questionnaires->material_count = $questionnaires->filter(function ($item) {
            if ($item->forward_material == 'true') {
                return $item;
            }
        })->count();

        $questionnaires->shenzhen_count = $questionnaires->filter(function ($item) {
            if (in_array('shenzhen', $item->trip_participation)) {
                return $item;
            }
        })->count();

        $questionnaires->beijing_count = $questionnaires->filter(function ($item) {
            if (in_array('beijing', $item->trip_participation)) {
                return $item;
            }
        })->count();

        $questionnaires->taipei_count = $questionnaires->filter(function ($item) {
            if (in_array('taipei', $item->trip_participation)) {
                return $item;
            }
        })->count();


        return $questionnaires;
    }
}
