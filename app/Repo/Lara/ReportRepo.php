<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\User;
use Backend\Repo\RepoInterfaces\ReportInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoInterfaces\EventApplicationInterface;
use Carbon;
use Backend\Repo\RepoTrait\PaginateTrait;

class ReportRepo implements ReportInterface
{
    use PaginateTrait;

    private $user_repo;
    private $event_repo;

    public function __construct(
        UserInterface              $user_repo,
        EventApplicationInterface  $even_repo
    ) {
        $this->user_repo  = $user_repo;
        $this->event_repo = $even_repo;
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
        $join_event_users = $this->event_repo->findByEventId($event_id);

        // filter complete & incomplete
        if ($complete) {
            $join_event_users = $join_event_users->filter(function ($item) {
                if ($item->applied_at) {
                    return $item;
                }
            });
        } else {
            $join_event_users = $join_event_users->filter(function ($item) {
                if (is_null($item->applied_at)) {
                    return $item;
                }
            });
        }

        if (isset($input['approve']) && !empty($input['approve'])) {
            if ($input['approve'] === 'approved') {
                $join_event_users = $join_event_users->filter(function ($item) {
                    if ($item->approved_at) {
                        return $item;
                    }
                });
            } elseif ($input['approve'] === 'no-approve') {
                $join_event_users = $join_event_users->filter(function ($item) {
                    if (is_null($item->approved_at)) {
                        return $item;
                    }
                });
            }
        }

        if (isset($input['email']) && !empty($input['email'])) {
            $email = trim($input['email']);
            $join_event_users = $join_event_users->filter(function ($item) use ($email) {
                if ($item->email === $email) {
                    return $item;
                }
            });
        }

        // filter user role
        if (isset($input['role']) && $input['role'] != 'all') {
            if ($input['role'] === 'expert') {
                $join_event_users = $join_event_users->filter(function ($item) {
                    if ($item->user->isExpert()) {
                        return $item;
                    }
                });
            } elseif ($input['role'] === 'creator') {
                $join_event_users = $join_event_users->filter(function ($item) {
                    if ($item->user->isCreator()) {
                        return $item;
                    }
                });
            }
        }

        // filter user id
        if (isset($input['user_id']) && !empty($input['user_id'])) {
            $user_id = trim($input['user_id']);
            $join_event_users = $join_event_users->filter(function ($item) use ($user_id) {
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
            $join_event_users = $join_event_users->filter(function ($item) use ($entered_at_start, $entered_at_end) {
                if ($item->entered_at <= $entered_at_end && $item->entered_at >= $entered_at_start) {
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
            $join_event_users = $join_event_users->filter(function ($item) use ($applied_at_start, $applied_at_end) {
                if ($item->applied_at <= $applied_at_end && $item->applied_at >= $applied_at_start) {
                    return $item;
                }
            });
        }

        $join_event_users = $this->event_repo->byCollectionPage($join_event_users, $page, $per_page);
        return $join_event_users;
    }

    public function getEventStatistics($complete, $join_event_users)
    {
        if ($complete) {
            $join_event_users->creator_count = $join_event_users->filter(function ($item) {
                if ($item->user->isCreator()) {
                    return $item;
                }
            })->count();

            $join_event_users->expert_count = $join_event_users->filter(function ($item) {
                if ($item->user->isExpert()) {
                    return $item;
                }
            })->count();

            $join_event_users->approved_count = $join_event_users->filter(function ($item) {
                if ($item->approved_at) {
                    return $item;
                }
            })->count();
        } else {
            $join_event_users->complete_count = $join_event_users->filter(function ($item) {
                if ($item->getCompleteTime()) {
                    return $item;
                }
            })->count();
        }

        return $join_event_users;
    }
}
