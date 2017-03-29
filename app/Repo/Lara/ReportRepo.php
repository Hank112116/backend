<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\User;
use Backend\Model\Eloquent\ProposeSolution;
use Backend\Model\Eloquent\GroupMemberApplicant;
use Backend\Model\Eloquent\ProjectMailExpert;
use Backend\Model\Eloquent\ProjectGroup;
use Backend\Model\Eloquent\ProposeProject;
use Backend\Model\Eloquent\MessageRelatedObject;
use Backend\Model\Report\Entity\MemberMatch;
use Backend\Model\Report\Entity\MemberMatchDetail;
use Backend\Model\Report\Entity\MemberMatchPM;
use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Repo\RepoInterfaces\ReportInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoTrait\PaginateTrait;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class ReportRepo implements ReportInterface
{
    use PaginateTrait;

    private $user_repo;
    private $project_repo;
    private $admin_repo;
    private $project_group;
    private $propose_solution;
    private $propose_project;
    private $group_member_applicant;
    private $recommend_expert;
    private $message_related;

    public function __construct(
        UserInterface               $user_repo,
        ProjectInterface            $project_repo,
        AdminerInterface            $admin_repo,
        MessageRelatedObject        $message_related,
        ProjectGroup                $project_group,
        ProposeProject              $propose_project,
        ProposeSolution             $propose_solution,
        GroupMemberApplicant        $group_member_applicant,
        ProjectMailExpert           $recommend_expert
    ) {
        $this->user_repo              = $user_repo;
        $this->project_repo           = $project_repo;
        $this->admin_repo             = $admin_repo;
        $this->project_group          = $project_group;
        $this->propose_project        = $propose_project;
        $this->propose_solution       = $propose_solution;
        $this->group_member_applicant = $group_member_applicant;
        $this->recommend_expert       = $recommend_expert;
        $this->message_related        = $message_related;
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

        switch ($filter) {
            case 'expert':
                $users = $this->user_repo->filterExperts($users);
                break;
            case 'premium-expert':
                $users = $this->user_repo->filterPremiumExperts($users);
                break;
            case 'creator':
                $users = $this->user_repo->filterCreator($users);
                break;
            case 'to-be-expert':
                $users = $this->user_repo->filterToBeExpert($users);
                break;
            case 'pm':
                $users = $this->user_repo->filterPM($users);
                break;
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

        switch ($filter) {
            case 'expert':
                $users = $this->user_repo->filterExperts($users);
                break;
            case 'creator':
                $users = $this->user_repo->filterCreator($users);
                break;
            case 'premium-expert':
                $users = $this->user_repo->filterPremiumExperts($users);
                break;
            case 'premium-creator':
                $users = $this->user_repo->filterPremiumCreator($users);
                break;
            case 'to-be-expert':
                $users = $this->user_repo->filterToBeExpert($users);
                break;
            case 'pm':
                $users = $this->user_repo->filterPM($users);
                break;
        }

        $summary = $this->getRegistrationSummary($users);

        $users = $this->user_repo->byCollectionPage($users, $page, $per_page);

        $users = $this->appendStatistics($users, $summary);
        return $users;
    }

    public function getMemberMatchingReport($input, $page, $per_page)
    {
        $dend = Carbon::parse($input['dend'])->addDay()->toDateString();

        $message_related = $this->message_related
            ->select(['log_id', 'message_id', 'sender_id', 'receiver_id', 'type', 'object_type', 'object_id', 'attached_at'])
            ->with(['sender', 'receiver'])
            ->whereBetween('attached_at', [$input['dstart'], $dend])
            ->where('has_submitted', true)
            ->whereIn('type', [MessageRelatedObject::TYPE_ATTACH, MessageRelatedObject::TYPE_PM_ATTACH])
            ->orderBy('attached_at', 'desc')
            ->get();

        $propose_solution = $this->propose_solution
            ->select(['log_id', 'proposer_id', 'propose_time', 'solution_id', 'project_id', 'event'])
            ->with(['user', 'project'])
            ->whereBetween('propose_time', [$input['dstart'], $dend])
            ->whereIn('event', [ProposeSolution::EVENT_PROPOSE, ProposeSolution::EVENT_SUGGEST])
            ->orderBy('propose_time', 'desc')
            ->get();

        $propose_project = $this->propose_project
            ->select(['log_id', 'proposer_id', 'propose_time', 'solution_id', 'project_id', 'event'])
            ->with(['user', 'solution'])
            ->whereBetween('propose_time', [$input['dstart'], $dend])
            ->whereIn('event', [ProposeProject::EVENT_PROPOSE, ProposeProject::EVENT_SUGGEST])
            ->orderBy('propose_time', 'desc')
            ->get();

        $group_member_applicant = $this->group_member_applicant
            ->select(['applicant_id', 'user_id', 'user_type', 'referral', 'apply_date', 'additional_privileges', 'event'])
            ->with(['user', 'referralUser'])
            ->whereBetween('apply_date', [$input['dstart'], $dend])
            ->where('user_type', '<>', User::TYPE_CREATOR)
            ->orderBy('apply_date', 'desc')
            ->get();

        $pms = $this->user_repo->findHWTrekPM();

        $pm_statistic = Collection::make();
        /* @var User $pm */
        foreach ($pms as $pm) {
            $pm_statistic->put($pm->id(), new MemberMatchPM($pm));
        }

        $collections = Collection::make();

        /***************************/
        /*  MessageRelatedObject   */
        /***************************/
        /* @var MessageRelatedObject $item */
        foreach ($message_related as $item) {
            if ($item->sender->id() === $item->receiver->id()) {
                continue;
            }
            $object_owner = $item->object->user;
            if ($collections->has($object_owner->id())) {
                $member_match = $collections->get($object_owner->id());

                $item->isPMAttach() ? $member_match->countPMReferrals() : $member_match->countUserReferrals();

                $collections->prepend($member_match, $object_owner->id());
            } else {
                $member_match = new MemberMatch($object_owner);

                $item->isPMAttach() ? $member_match->countPMReferrals() : $member_match->countUserReferrals();

                $collections->put($object_owner->id(), $member_match);
            }

            if ($collections->has($item->receiver->id())) {
                $member_match = $collections->get($item->receiver->id());

                $item->isPMAttach() ? $member_match->countPMRecommend() : $member_match->countUserRecommend();

                $collections->prepend($member_match, $item->receiver->id());
            } else {
                $member_match = new MemberMatch($item->receiver);

                $item->isPMAttach() ? $member_match->countPMRecommend() : $member_match->countUserRecommend();

                $collections->put($item->receiver->id(), $member_match);
            }

            $pm_statistic = $this->statisticPM($pm_statistic, $item->sender);
        }
        /***************************/
        /*  ProposeSolution   */
        /***************************/
        /* @var ProposeSolution $item */
        foreach ($propose_solution as $item) {
            // Project owner recommend
            if ($collections->has($item->projectOwner()->id())) {
                $member_match = $collections->get($item->projectOwner()->id());

                $item->isPMPropose() ? $member_match->countPMRecommend() : $member_match->countUserRecommend();

                $collections->prepend($member_match, $item->projectOwner()->id());
            } else {
                $member_match = new MemberMatch($item->projectOwner());

                $item->isPMPropose() ? $member_match->countPMRecommend() : $member_match->countUserRecommend();

                $collections->put($item->projectOwner()->id(), $member_match);
            }

            // Solution owner referral
            if ($collections->has($item->solutionOwner()->id())) {
                $member_match = $collections->get($item->solutionOwner()->id());

                $item->isPMPropose() ? $member_match->countPMReferrals() : $member_match->countUserReferrals();

                $collections->prepend($member_match, $item->solutionOwner()->id());
            } else {
                $member_match = new MemberMatch($item->solutionOwner());

                $item->isPMPropose() ? $member_match->countPMReferrals() : $member_match->countUserReferrals();

                $collections->put($item->solutionOwner()->id(), $member_match);
            }

            $pm_statistic = $this->statisticPM($pm_statistic, $item->user);
        }

        /***************************/
        /*  ProposeProject   */
        /***************************/
        /* @var ProposeProject $item */
        foreach ($propose_project as $item) {
            // Solution owner recommend
            if ($collections->has($item->solutionOwner()->id())) {
                $member_match = $collections->get($item->solutionOwner()->id());

                $item->isPMPropose() ? $member_match->countPMRecommend() : $member_match->countUserRecommend();

                $collections->prepend($member_match, $item->solutionOwner()->id());
            } else {
                $member_match = new MemberMatch($item->solutionOwner());

                $item->isPMPropose() ? $member_match->countPMRecommend() : $member_match->countUserRecommend();

                $collections->put($item->solutionOwner()->id(), $member_match);
            }

            // Project owner referral
            if ($collections->has($item->projectOwner()->id())) {
                $member_match = $collections->get($item->projectOwner()->id());

                $item->isPMPropose() ? $member_match->countPMReferrals() : $member_match->countUserReferrals();

                $collections->prepend($member_match, $item->projectOwner()->id());
            } else {
                $member_match = new MemberMatch($item->projectOwner());

                $item->isPMPropose() ? $member_match->countPMReferrals() : $member_match->countUserReferrals();

                $collections->put($item->projectOwner()->id(), $member_match);
            }

            $pm_statistic = $this->statisticPM($pm_statistic, $item->user);
        }
        /***************************/
        /*  GroupMemberApplicant   */
        /***************************/
        /* @var GroupMemberApplicant $item */
        foreach ($group_member_applicant as $item) {
            if ($item->isMemberReferral() or $item->isSelfReferral()) {
                if ($collections->has($item->projectOwner()->id())) {
                    $member_match = $collections->get($item->projectOwner()->id());

                    if ($item->isPMReferral()) {
                        $member_match->countPMRecommend();
                    } elseif ($item->isUserReferral()) {
                        $member_match->countUserRecommend();
                    }

                    $collections->prepend($member_match, $item->projectOwner()->id());
                } else {
                    $member_match = new MemberMatch($item->projectOwner());

                    if ($item->isPMReferral()) {
                        $member_match->countPMRecommend();
                    } elseif ($item->isUserReferral()) {
                        $member_match->countUserRecommend();
                    }

                    $collections->put($item->projectOwner()->id(), $member_match);
                }
            }

            if ($item->isSelfReferral()) {
                if ($collections->has($item->user->id())) {
                    $member_match = $collections->get($item->user->id());

                    if ($item->isPMReferral()) {
                        $member_match->countPMReferrals();
                    } elseif ($item->isUserReferral()) {
                        $member_match->countUserReferrals();
                    }

                    $collections->prepend($member_match, $item->user->id());
                } else {
                    $member_match = new MemberMatch($item->user);

                    if ($item->isPMReferral()) {
                        $member_match->countPMReferrals();
                    } elseif ($item->isUserReferral()) {
                        $member_match->countUserReferrals();
                    }

                    $collections->put($item->user->id(), $member_match);
                }

                $pm_statistic = $this->statisticPM($pm_statistic, $item->user);
            } elseif ($item->isMemberReferral()) {
                if ($collections->has($item->user->id())) {
                    $member_match = $collections->get($item->user->id());

                    if ($item->isPMReferral()) {
                        $member_match->countPMReferrals();
                    } elseif ($item->isUserReferral()) {
                        $member_match->countUserReferrals();
                    }

                    $collections->prepend($member_match, $item->user->id());
                } else {
                    $member_match = new MemberMatch($item->user);

                    if ($item->isPMReferral()) {
                        $member_match->countPMReferrals();
                    } elseif ($item->isUserReferral()) {
                        $member_match->countUserReferrals();
                    }

                    $collections->put($item->user->id(), $member_match);
                }

                $pm_statistic = $this->statisticPM($pm_statistic, $item->referralUser);
            }
        }

        $collections = $this->filterMemberMatchingReport($collections, $input);

        // sort collections by collection index
        $tmp = $collections->toArray();

        krsort($tmp);

        $result = Collection::make($tmp);

        $result = $this->getPaginateFromCollection($result, $page, $per_page);

        $result->pm_statistic = $pm_statistic;

        return $result;
    }

    private function statisticPM(Collection $collection, User $user)
    {
        if ($collection->has($user->id())) {
            $statistic = $collection->get($user->id());

            $statistic->countReferral();

            $collection->prepend($statistic, $user->id());
        }

        return $collection;
    }

    private function filterMemberMatchingReport(Collection $collections, $input)
    {
        if (!empty($input['user_id'])) {
            $user_id = $input['user_id'];

            $collections = $collections->filter(function (MemberMatch $item) use ($user_id) {
                if ($item->id() == $user_id) {
                    return $item;
                }
            });
        }

        if (!empty($input['user_name'])) {
            $user_name = $input['user_name'];

            $collections = $collections->filter(function (MemberMatch $item) use ($user_name) {
                if (stristr($item->fullName(), $user_name)) {
                    return $item;
                }
            });
        }

        if (!empty($input['company'])) {
            $company = $input['company'];

            $collections = $collections->filter(function (MemberMatch $item) use ($company) {
                if (stristr($item->company(), $company)) {
                    return $item;
                }
            });
        }

        if (!empty($input['action'])) {
            $action = $input['action'];

            $collections = $collections->filter(function (MemberMatch $item) use ($action) {
                if (stristr($item->internalAction(), $action)) {
                    return $item;
                }
            });
        }

        if (!empty($input['status']) && $input['status'] != 'all') {
            $status =  $input['status'];

            $collections = $collections->filter(function (MemberMatch $item) use ($status) {
                switch ($status) {
                    case 'creator':
                        if ($item->isBasicCreator()) {
                            return $item;
                        }
                        break;
                    case 'expert':
                        if ($item->isBasicExpert()) {
                            return $item;
                        }
                        break;
                    case 'premium-creator':
                        if ($item->isPremiumCreator()) {
                            return $item;
                        }
                        break;
                    case 'premium-expert':
                        if ($item->isPremiumExpert()) {
                            return $item;
                        }
                        break;
                    case 'to-be-expert':
                        if ($item->isPendingExpert()) {
                            return $item;
                        }
                        break;
                    case 'pm':
                        if ($item->isHWTrekPM()) {
                            return $item;
                        }
                        break;
                }
            });
        }

        return $collections;
    }

    /**
     * @param $user_id
     * @param $dstart
     * @param $dend
     * @return array
     */
    public function getDetailMatchingDataByUser($user_id, $dstart, $dend)
    {
        $data['recommend']['profile']  = [];
        $data['recommend']['project']  = [];
        $data['recommend']['solution'] = [];
        $data['referrals']['profile']  = [];
        $data['referrals']['project']  = [];
        $data['referrals']['solution'] = [];

        $dend = Carbon::parse($dend)->addDay()->toDateString();
        $user = $this->user_repo->find($user_id);
        $solutions = $user->solutions->pluck('solution_id');
        $projects  = $user->projects->pluck('project_id');

        /***************************/
        /*  MessageRelatedObject   */
        /***************************/
        // Attach recommend
        $attach_recommends = $this->message_related->select(['log_id', 'message_id', 'sender_id', 'receiver_id', 'type', 'object_type', 'object_id', 'attached_at'])
            ->whereBetween('attached_at', [$dstart, $dend])
            ->where('receiver_id', $user_id)
            ->where('has_submitted', true)
            ->where('sender_id', '<>', $user_id)
            ->whereIn('type', [MessageRelatedObject::TYPE_ATTACH, MessageRelatedObject::TYPE_PM_ATTACH])
            ->orderBy('attached_at', 'desc')
            ->get();

        // Attach project referrals
        $attach_project_referrals = $this->message_related->select(['log_id', 'message_id', 'sender_id', 'receiver_id', 'type', 'object_type', 'object_id', 'attached_at'])
            ->whereBetween('attached_at', [$dstart, $dend])
            ->where('object_type', 'project')
            ->where('has_submitted', true)
            ->whereIn('object_id', $projects)
            ->whereIn('type', [MessageRelatedObject::TYPE_ATTACH, MessageRelatedObject::TYPE_PM_ATTACH])
            ->orderBy('attached_at', 'desc')
            ->get();

        // Attach solution referrals
        $attach_solution_referrals = $this->message_related->select(['log_id', 'message_id', 'sender_id', 'receiver_id', 'type', 'object_type', 'object_id', 'attached_at'])
            ->whereBetween('attached_at', [$dstart, $dend])
            ->where('object_type', 'solution')
            ->where('has_submitted', true)
            ->whereIn('object_id', $solutions)
            ->whereIn('type', [MessageRelatedObject::TYPE_ATTACH, MessageRelatedObject::TYPE_PM_ATTACH])
            ->orderBy('attached_at', 'desc')
            ->get();

        /* @var MessageRelatedObject $recommend */
        foreach ($attach_recommends as $recommend) {
            $r = [];
            $r['operator']     = $recommend->sender;
            $r['action']       = 'recommend';
            $r['object']       = $recommend->objectType();
            $r['object_id']    = $recommend->objectId();
            $r['receive_type'] = MemberMatchDetail::OBJECT_MEMBER;
            $r['receiver']     = $recommend->receiver;
            $r['on_time']   = $recommend->attachedAt();
            $data['recommend']['profile'][] = new MemberMatchDetail($r);
        }

        /* @var MessageRelatedObject $referral */
        foreach ($attach_project_referrals as $referral) {
            if ($referral->sender->id() == $referral->receiver->id()) {
                continue;
            }
            $r = [];
            $r['operator']     = $referral->sender;
            $r['action']       = 'referrals';
            $r['object']       = $referral->object_type;
            $r['object_id']    = $referral->objectId();
            $r['receive_type'] = MemberMatchDetail::OBJECT_MEMBER;
            $r['receiver']     = $referral->receiver;
            $r['on_time']      = $referral->attachedAt();
            $data['referrals']['project'][] = new MemberMatchDetail($r);
        }

        /* @var MessageRelatedObject $referral */
        foreach ($attach_solution_referrals as $referral) {
            if ($referral->sender->id() == $referral->receiver->id()) {
                continue;
            }
            $r = [];
            $r['operator']     = $referral->sender;
            $r['action']       = 'referrals';
            $r['object']       = $referral->object_type;
            $r['object_id']    = $referral->objectId();
            $r['receive_type'] = MemberMatchDetail::OBJECT_MEMBER;
            $r['receiver']     = $referral->receiver;
            $r['on_time']      = $referral->attachedAt();
            $data['referrals']['solution'][] = new MemberMatchDetail($r);
        }

        /***************************/
        /*  ProposeSolution   */
        /***************************/
        $propose_solution_recommends = $this->propose_solution
            ->select(['log_id', 'proposer_id', 'propose_time', 'solution_id', 'project_id', 'event'])
            ->with(['project'])
            ->whereBetween('propose_time', [$dstart, $dend])
            ->whereIn('project_id', $projects)
            ->whereIn('event', [ProposeSolution::EVENT_PROPOSE, ProposeSolution::EVENT_SUGGEST])
            ->orderBy('propose_time', 'desc')
            ->get();

        $propose_solution_referrals = $this->propose_solution
            ->select(['log_id', 'proposer_id', 'propose_time', 'solution_id', 'project_id', 'event'])
            ->with(['project'])
            ->whereBetween('propose_time', [$dstart, $dend])
            ->whereIn('solution_id', $solutions)
            ->whereIn('event', [ProposeSolution::EVENT_PROPOSE, ProposeSolution::EVENT_SUGGEST])
            ->orderBy('propose_time', 'desc')
            ->get();

        /* @var ProposeSolution $recommend */
        foreach ($propose_solution_recommends as $recommend) {
            $r = [];
            $r['operator']     = $recommend->user;
            $r['action']       = 'recommend';
            $r['object']       = MemberMatchDetail::OBJECT_SOLUTION;
            $r['object_id']    = $recommend->solutionId();
            $r['receive_type'] = MemberMatchDetail::OBJECT_PROJECT;
            $r['receiver']     = $recommend->project;
            $r['on_time']      = $recommend->proposeTime();
            $data['recommend']['project'][] = new MemberMatchDetail($r);
        }

        /* @var ProposeSolution $referral */
        foreach ($propose_solution_referrals as $referral) {
            $r = [];
            $r['operator']     = $referral->user;
            $r['action']       = 'referrals';
            $r['object']       = MemberMatchDetail::OBJECT_SOLUTION;
            $r['object_id']    = $referral->solutionId();
            $r['receive_type'] = MemberMatchDetail::OBJECT_PROJECT;
            $r['receiver']     = $referral->project;
            $r['on_time']      = $referral->proposeTime();
            $data['referrals']['solution'][] = new MemberMatchDetail($r);
        }

        /***************************/
        /*  ProposeProject   */
        /***************************/
        $propose_project_recommends = $this->propose_project
            ->select(['log_id', 'proposer_id', 'propose_time', 'solution_id', 'project_id', 'event'])
            ->with(['solution'])
            ->whereBetween('propose_time', [$dstart, $dend])
            ->whereIn('solution_id', $solutions)
            ->whereIn('event', [ProposeProject::EVENT_PROPOSE, ProposeProject::EVENT_SUGGEST])
            ->orderBy('propose_time', 'desc')
            ->get();

        $propose_project_referrals = $this->propose_project
            ->select(['log_id', 'proposer_id', 'propose_time', 'solution_id', 'project_id', 'event'])
            ->with(['solution'])
            ->whereBetween('propose_time', [$dstart, $dend])
            ->whereIn('project_id', $projects)
            ->whereIn('event', [ProposeProject::EVENT_PROPOSE, ProposeProject::EVENT_SUGGEST])
            ->orderBy('propose_time', 'desc')
            ->get();

        /* @var ProposeProject $recommend */
        foreach ($propose_project_recommends as $recommend) {
            $r = [];
            $r['operator']     = $recommend->user;
            $r['action']       = 'recommend';
            $r['object']       = MemberMatchDetail::OBJECT_PROJECT;
            $r['object_id']    = $recommend->projectId();
            $r['receive_type'] = MemberMatchDetail::OBJECT_SOLUTION;
            $r['receiver']     = $recommend->solution;
            $r['on_time']      = $recommend->proposeTime();
            $data['recommend']['solution'][] = new MemberMatchDetail($r);
        }

        /* @var ProposeProject $referral */
        foreach ($propose_project_referrals as $referral) {
            $r = [];
            $r['operator']     = $referral->user;
            $r['action']       = 'referrals';
            $r['object']       = MemberMatchDetail::OBJECT_PROJECT;
            $r['object_id']    = $referral->projectId();
            $r['receive_type'] = MemberMatchDetail::OBJECT_SOLUTION;
            $r['receiver']     = $referral->solution;
            $r['on_time']      = $referral->proposeTime();
            $data['referrals']['project'][] = new MemberMatchDetail($r);
        }

        /***************************/
        /*  GroupMemberApplicant   */
        /***************************/
        $project_groups = $this->project_group
            ->select('group_id')
            ->whereIn('project_id', $projects)
            ->get()
            ->pluck('group_id');

        $group_member_applicant_recommends = $this->group_member_applicant
            ->select(['applicant_id', 'user_id', 'user_type', 'referral', 'apply_date', 'additional_privileges', 'event'])
            ->with(['user', 'referralUser'])
            ->whereBetween('apply_date', [$dstart, $dend])
            ->whereIn('group_id', $project_groups)
            ->where('user_type', '<>', User::TYPE_CREATOR)
            ->orderBy('apply_date', 'desc')
            ->get();

        $group_member_applicant_self_referrals = $this->group_member_applicant
            ->select(['applicant_id', 'user_id', 'user_type', 'referral', 'apply_date', 'additional_privileges', 'event'])
            ->with(['user', 'referralUser'])
            ->whereBetween('apply_date', [$dstart, $dend])
            ->where('user_id', $user_id)
            ->whereNull('referral')
            ->where('user_type', '<>', User::TYPE_CREATOR)
            ->whereIn('event', [GroupMemberApplicant::APPLY_PM, GroupMemberApplicant::APPLY_USER])
            ->orderBy('apply_date', 'desc')
            ->get();
        $group_member_applicant_referrals = $this->group_member_applicant
            ->select(['applicant_id', 'user_id', 'user_type', 'referral', 'apply_date', 'additional_privileges', 'event'])
            ->with(['user', 'referralUser'])
            ->whereBetween('apply_date', [$dstart, $dend])
            ->where('user_id', $user_id)
            ->whereNotNull('referral')
            ->where('user_type', '<>', User::TYPE_CREATOR)
            ->orderBy('apply_date', 'desc')
            ->get();

        /* @var GroupMemberApplicant  $recommend */
        foreach ($group_member_applicant_recommends as $recommend) {
            $r = [];

            if ($recommend->isSelfReferral() or $recommend->isMemberReferral()) {
                $r['operator'] = $recommend->isSelfReferral() ? $recommend->user : $recommend->referralUser;
                $r['action'] = 'recommend';
                $r['object'] = MemberMatchDetail::OBJECT_MEMBER;
                $r['object_id'] = $recommend->user->id();
                $r['receive_type'] = MemberMatchDetail::OBJECT_PROJECT;
                $r['receiver'] = $recommend->project();
                $r['on_time'] = $recommend->applyDate();
                $data['recommend']['project'][] = new MemberMatchDetail($r);
            }
        }

        /* @var GroupMemberApplicant  $referral */
        foreach ($group_member_applicant_self_referrals as $referral) {
            $r = [];
            $r['operator']     = $user;
            $r['action']       = 'referrals';
            $r['object']       = MemberMatchDetail::OBJECT_MEMBER;
            $r['object_id']    = $referral->user->id();
            $r['receive_type'] = MemberMatchDetail::OBJECT_PROJECT;
            $r['receiver']     = $referral->project();
            $r['on_time']      = $referral->applyDate();
            $data['referrals']['profile'][] = new MemberMatchDetail($r);
        }

        /* @var GroupMemberApplicant  $referral */
        foreach ($group_member_applicant_referrals as $referral) {
            $r = [];
            $r['operator']     = $referral->referralUser;
            $r['action']       = 'referrals';
            $r['object']       = MemberMatchDetail::OBJECT_MEMBER;
            $r['object_id']    = $referral->user->id();
            $r['receive_type'] = MemberMatchDetail::OBJECT_PROJECT;
            $r['receiver']     = $referral->project();
            $r['on_time']      = $referral->applyDate();

            $data['referrals']['profile'][] = new MemberMatchDetail($r);
        }

        return $this->refactorMemberMatchingDate($data);
    }

    private function refactorMemberMatchingDate(array $data)
    {
        $recommend_profile   = $data['recommend']['profile'];
        $recommend_projects  = $data['recommend']['project'];
        $recommend_solutions = $data['recommend']['solution'];
        $referrals_profile   = $data['referrals']['profile'];
        $referrals_projects  = $data['referrals']['project'];
        $referrals_solutions = $data['referrals']['solution'];

        $profile = [];
        /* @var MemberMatchDetail $item */
        foreach ($recommend_profile as $item) {
            if (!array_key_exists($item->receiverId(), $profile)) {
                $profile[$item->receiverId()]['object_id']   = $item->receiverId();
                $profile[$item->receiverId()]['object_name'] = $item->receiverName();
                $profile[$item->receiverId()]['object_link'] = $item->receiverLink();
                $profile[$item->receiverId()]['data'][]      = $item;
            } else {
                $profile[$item->receiverId()]['data'][] = $item;
            }
        }
        $data['recommend']['profile'] = $profile;

        $profile = [];
        /* @var MemberMatchDetail $item */
        foreach ($referrals_profile as $item) {
            if (!array_key_exists($item->receiverId(), $profile)) {
                $profile[$item->receiverId()]['object_id']   = $item->receiverId();
                $profile[$item->receiverId()]['object_name'] = $item->receiverName();
                $profile[$item->receiverId()]['object_link'] = $item->receiverLink();
                $profile[$item->receiverId()]['data'][]      = $item;
            } else {
                $profile[$item->receiverId()]['data'][] = $item;
            }
        }
        $data['referrals']['profile'] = $profile;

        $projects = [];
        /* @var MemberMatchDetail $item */
        foreach ($recommend_projects as $item) {
            if (!array_key_exists($item->receiverId(), $projects)) {
                $projects[$item->receiverId()]['object_id']   = $item->receiverId();
                $projects[$item->receiverId()]['object_name'] = $item->receiverName();
                $projects[$item->receiverId()]['object_link'] = $item->receiverLink();
                $projects[$item->receiverId()]['data'][]      = $item;
            } else {
                $projects[$item->receiverId()]['data'][] = $item;
            }
        }
        $data['recommend']['project'] = $projects;

        $projects = [];
        /* @var MemberMatchDetail $item */
        foreach ($referrals_projects as $item) {
            if (!array_key_exists($item->objectId(), $projects)) {
                $projects[$item->objectId()]['object_id']   = $item->objectId();
                $projects[$item->objectId()]['object_name'] = $item->objectName();
                $projects[$item->objectId()]['object_link'] = $item->objectLink();
                $projects[$item->objectId()]['data'][]      = $item;
            } else {
                $projects[$item->objectId()]['data'][] = $item;
            }
        }
        $data['referrals']['project'] = $projects;

        $solutions = [];
        /* @var MemberMatchDetail $item */
        foreach ($recommend_solutions as $item) {
            if (!array_key_exists($item->receiverId(), $solutions)) {
                $solutions[$item->receiverId()]['object_id']   = $item->receiverId();
                $solutions[$item->receiverId()]['object_name'] = $item->receiverName();
                $solutions[$item->receiverId()]['object_link'] = $item->receiverLink();
                $solutions[$item->receiverId()]['data'][]      = $item;
            } else {
                $solutions[$item->receiverId()]['data'][] = $item;
            }
        }
        $data['recommend']['solution'] = $solutions;

        $solutions = [];
        /* @var MemberMatchDetail $item */
        foreach ($referrals_solutions as $item) {
            if (!array_key_exists($item->objectId(), $solutions)) {
                $solutions[$item->objectId()]['object_id']   = $item->objectId();
                $solutions[$item->objectId()]['object_name'] = $item->objectName();
                $solutions[$item->objectId()]['object_link'] = $item->objectLink();
                $solutions[$item->objectId()]['data'][]      = $item;
            } else {
                $solutions[$item->objectId()]['data'][] = $item;
            }
        }
        $data['referrals']['solution'] = $solutions;

        return $data;
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
            if ($item->isSuspended()) {
                return $item;
            }
        })->count();

        $result['active'] = $users->filter(function (User $item) {
            if ($item->isActive() and $item->isEmailVerify() and !$item->isSuspended()) {
                return $item;
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
