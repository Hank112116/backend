<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\User;
use Backend\Repo\RepoInterfaces\ReportInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Carbon;
use Backend\Repo\RepoTrait\PaginateTrait;

class ReportRepo implements ReportInterface
{

    use PaginateTrait;
    private $user_repo;

    public function __construct(
        UserInterface $user_repo
    ) {
        $this->user_repo = $user_repo;
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
}
