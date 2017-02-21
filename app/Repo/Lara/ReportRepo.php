<?php namespace Backend\Repo\Lara;

use Backend\Enums\EventEnum;
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
use Backend\Repo\RepoInterfaces\EventApplicationInterface;
use Backend\Repo\RepoInterfaces\EventQuestionnaireInterface;
use Backend\Repo\RepoTrait\PaginateTrait;
use Backend\Model\Eloquent\EventApplication;
use Illuminate\Database\Eloquent\Collection;
use Carbon;

class ReportRepo implements ReportInterface
{
    use PaginateTrait;

    private $user_repo;
    private $project_repo;
    private $admin_repo;
    private $event_repo;
    private $questionnaire_repo;
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
        EventApplicationInterface   $even_repo,
        EventQuestionnaireInterface $questionnaire_repo,
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
        $this->event_repo             = $even_repo;
        $this->questionnaire_repo     = $questionnaire_repo;
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
                        if ($item->isCreator() and !$item->isPremiumCreator()) {
                            return $item;
                        }
                        break;
                    case 'expert':
                        if ($item->isExpert() and !$item->isHWTrekPM() and !$item->isPremiumExpert()) {
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

    private function getQuestionnaireSummary(Collection $approve_event_users, $input)
    {
        $summary = [
            'ait_form_rejected'     => 0,
            'ait_form_ongoing'      => 0,
            'ait_form_completed'    => 0,
            'ait_dinner'            => 0,
            'ait_sz'                => 0,
            'ait_kyoto'             => 0,
            'ait_osaka'             => 0,
            'ait_guest_dinner'      => 0,
            'ait_guest_sz'          => 0,
            'ait_guest_kyoto'       => 0,
            'ait_guest_osaka'       => 0
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
            $questionnaire = $this->questionnaire_repo->findByApproveUser($approve_event_user);
            if (empty($questionnaire)) {
                continue;
            }
            switch ($questionnaire->form_status) {
                case 'Rejected':
                    $summary['ait_form_rejected'] = $summary['ait_form_rejected'] + 1;
                    break;
                case 'Ongoing':
                    $summary['ait_form_ongoing'] = $summary['ait_form_ongoing'] + 1;
                    break;
                case 'Completed':
                    $summary['ait_form_completed'] = $summary['ait_form_completed'] + 1;
                    break;
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
                }
            }
        }
        return $summary;
    }
}
