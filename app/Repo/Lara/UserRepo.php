<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\User;
use Backend\Model\Eloquent\InternalUserMemo;
use Backend\Repo\RepoInterfaces\ApplyExpertMessageInterface;
use ImageUp;
use Validator;
use Carbon;
use Illuminate\Database\Eloquent\Collection;
use Backend\Repo\RepoInterfaces\ExpertiseInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoTrait\PaginateTrait;

class UserRepo implements UserInterface
{
    use PaginateTrait;

    protected $with_relations = ['internalUserMemo'];

    private $error;
    private $user;
    private $user_memo;
    private $expertise;
    private $apply_expert_msg_repo;
    private $image_uplodaer;

    private static $update_columns = [
        'active', 'email_verify', 'user_type', 'email',
        'user_name', 'last_name', 'country', 'city',
        'company', 'company_url', 'personal_url', 'business_id',
        'user_about', 'expertises',
    ];

    private $update_memo_columns = [
        'description', 'tags', 'report_action'
    ];

    public function __construct(
        User $user,
        InternalUserMemo $user_memo,
        ExpertiseInterface $expertise,
        ApplyExpertMessageInterface $apply_expert_msg_repo,
        ImageUp $image_uploader
    ) {
        $this->user                  = $user;
        $this->user_memo             = $user_memo;
        $this->expertise             = $expertise;
        $this->apply_expert_msg_repo = $apply_expert_msg_repo;
        $this->image_uplodaer        = $image_uploader;
    }

    public function dummy()
    {
        return new User();
    }

    public function find($id)
    {
        return $this->user->find($id);
    }

    public function all()
    {
        return $this->user->all();
    }

    public function findWithDetail($id)
    {
        $user = $this->user->with(
            'projects',
            'solutions',
            'applyExpertMessage'
        )->find($id);

        return $user;
    }

    public function experts($page = 1, $limit = 20)
    {
        $this->setPaginateTotal($this->user->queryExperts()->count());
        $users = $this->modelBuilder($this->user, $page, $limit)
            ->where('user_type', User::TYPE_EXPERT)
            ->get();

        return $this->getPaginateContainer($this->user, $page, $limit, $users);
    }

    public function findExpert($id)
    {
        return $this->user->where('user_id', $id)->where('user_type', User::TYPE_EXPERT)->get();
    }

    public function creators($page = 1, $limit = 20)
    {
        $this->setPaginateTotal($this->user->queryCreators()->count());
        $users = $this->modelBuilder($this->user, $page, $limit)
            ->where('user_type', User::TYPE_CREATOR)
            ->get();

        return $this->getPaginateContainer($this->user, $page, $limit, $users);
    }

    public function toBeExpertMembers()
    {
        $ids = $this->toBeExpertMemberIds();
        if (!$ids) {
            return false;
        }

        return $this->user->whereIn('user_id', $ids)
            ->orderBy('user_id', 'desc')
            ->get();
    }

    public function toBeExpertMemberIds()
    {
        return $this->user
            ->orWhere('is_sign_up_as_expert', '1')
            ->orWhere('is_apply_to_be_expert', '1')
            ->where('user_type', User::TYPE_CREATOR)
            ->lists('user_id');
    }

    public function byPage($page = 1, $limit = 20)
    {
        $users = $this->modelBuilder($this->user, $page, $limit)
            ->with($this->with_relations)
            ->get();
        return $this->getPaginateContainer($this->user, $page, $limit, $users);
    }

    public function byCollectionPage($collection, $page = 1, $per_page = 20)
    {
        return $this->getPaginateFromCollection($collection, $page, $per_page);
    }

    public function byId($id = '')
    {
        return $this->user->where('user_id', $id)->get();
    }

    /*
     * Return User Collection By name
     *
     * @param str $name
     * @return Illuminate\Support\Collection $collection
     */
    public function byName($name = '')
    {
        if (!$name) {
            return new Collection();
        };

        $name_trimmed = preg_replace('/\s+/', ' ', $name); //replace mutiple space to one
        $keys         = explode(' ', $name_trimmed);

        $user_builder = $this->user->orderBy('user_id', 'desc');
        foreach ($keys as $k) {
            $user_builder
                ->orWhere('user_name', 'LIKE', "%{$k}%")
                ->orWhere('last_name', 'LIKE', "%{$k}%");
        }

        return $user_builder->get();
    }

    public function byMail($email = '')
    {
        return $this->byLikeSearch('email', $email);
    }

    public function byCompany($company = '')
    {
        return $this->byLikeSearch('company', $company);
    }

    public function byLikeSearch($column, $value)
    {
        if (!$value) {
            return new Collection();
        };

        return $this->user
            ->where($column, 'LIKE', "%{$value}%")
            ->orderBy('user_id', 'desc')
            ->get();
    }

    public function byDateRange($dstart = '', $dend = '')
    {
        if (!$dstart and !$dend) {
            return new Collection();
        }

        $dstart = $dstart ? Carbon::parse($dstart) : Carbon::now()->startOfMonth();
        $dend   = $dend ? Carbon::parse($dend)->addDay() : Carbon::now();

        return $this->user->whereBetween('date_added', [ $dstart, $dend ])
            ->orderBy('user_id', 'desc')
            ->get();
    }

    public function byUnionSearch($input, $page, $per_page)
    {
        /* @var Collection $users */
        $users = $this->user->orderBy('user_id', 'desc')->get();

        if (!empty($input['user_name'])) {
            $user_name = $input['user_name'];
            $users = $users->filter(function (User $item) use ($user_name) {
                if (stristr($item->textFullName(), $user_name)) {
                    return $item;
                }
            });
        }

        if (!empty($input['user_id'])) {
            $user_id = $input['user_id'];
            $users = $users->filter(function (User $item) use ($user_id) {
                if ($item->user_id == $user_id) {
                    return $item;
                }
            });
        }

        if (!empty($input['email'])) {
            $email = $input['email'];
            $users = $users->filter(function (User $item) use ($email) {
                if (stristr($item->email, $email)) {
                    return $item;
                }
            });
        }

        if (!empty($input['company'])) {
            $company = $input['company'];
            $users = $users->filter(function (User $item) use ($company) {
                if (stristr($item->company, $company)) {
                    return $item;
                }
            });
        }

        if (!empty($input['dstart'])) {
            $dstart = $input['dstart'];

            if (!empty($input['dend'])) {
                $dend = Carbon::parse($input['dend'])->addDay()->toDateString();
            } else {
                $dend = Carbon::tomorrow()->toDateString();
            }
            $users = $users->filter(function (User $item) use ($dstart, $dend) {
                $create_time = Carbon::parse($item->date_added)->toDateString();
                if ($create_time < $dend && $create_time >= $dstart) {
                    return $item;
                }
            });
        }

        if (!empty($input['status'])) {
            if ($input['status'] != 'all') {
                $status   = $input['status'];
                $users = $users->filter(function (User $item) use ($status) {
                    switch ($status) {
                        case 'expert':
                            if ($item->isExpert()) {
                                return $item;
                            }
                            break;
                        case 'creator':
                            if ($item->isCreator()) {
                                return $item;
                            }
                            break;
                        case 'to-be-expert':
                            if ($item->isToBeExpert() or $item->isApplyExpert()) {
                                return $item;
                            }
                            break;
                        case 'premium-expert':
                            if ($item->isPremiumExpert()) {
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
        }

        if (!empty($input['description'])) {
            $description = $input['description'];
            $users = $users->filter(function (User $item) use ($description) {
                if ($item->internalUserMemo) {
                    if (stristr($item->internalUserMemo->description, $description)) {
                        return $item;
                    }
                }
            });
        }

        if (!empty($input['tag'])) {
            $search_tag = $input['tag'];
            $users   = $users->filter(function (User $item) use ($search_tag) {
                $internal_tag = [];

                if ($item->internalUserMemo) {
                    if ($item->internalUserMemo->tags) {
                        $internal_tag = explode(',', $item->internalUserMemo->tags);
                    }
                    if ($internal_tag) {
                        foreach ($internal_tag as $tag) {
                            if (stristr($tag, $search_tag)) {
                                return $item;
                            }
                        }
                    }
                }
                if ($item->expertises) {
                    $expertise_tags = $this->expertise->getDisplayTags(explode(',', $item->expertises));
                    foreach ($expertise_tags as $tag) {
                        if (stristr($tag, $search_tag)) {
                            return $item;
                        }
                    }
                }
            });
        }

        return $this->getPaginateFromCollection($users, $page, $per_page);
    }

    public function filterExpertsWithToBeExperts(Collection $users)
    {
        return $users->filter(
            function (User $user) {
                return $user->isExpert() || $user->isToBeExpert();
            }
        );
    }

    public function filterCreatorWithoutToBeExperts(Collection $users)
    {
        return $users->filter(
            function (User $user) {
                return $user->isCreator() && !$user->isToBeExpert();
            }
        );
    }

    public function filterExperts(Collection $users)
    {
        return $users->filter(
            function (User $user) {
                return $user->isExpert();
            }
        );
    }

    public function filterCreator(Collection $users)
    {
        return $users->filter(
            function (User $user) {
                return $user->isCreator();
            }
        );
    }

    public function filterPM(Collection $users)
    {
        return $users->filter(
            function (User $user) {
                return $user->isHWTrekPM();
            }
        );
    }

    public function getCommentCountsByDateById($dstart, $dend, $id)
    {
        return $this
            ->withCommentCountsByDate($dstart, $dend)
            ->byId($id);
    }

    public function getCommentCountsByDateByName($dstart, $dend, $name)
    {
        return $this
            ->withCommentCountsByDate($dstart, $dend)
            ->byName($name);
    }

    public function getCommentCountsByDate($dstart, $dend)
    {
        return $this
            ->withCommentCountsByDate($dstart, $dend)
            ->user->get();
    }

    private function withCommentCountsByDate($dstart, $dend)
    {
        $dstart = $dstart ? Carbon::parse($dstart) : Carbon::now()->startOfMonth();
        $dend   = $dend ? Carbon::parse($dend)->addDay() : Carbon::now();

        $this->user = $this->user->with([
            'sendProjectSolutionCommentCount'    => function ($q) use ($dstart, $dend) {
                $q->where('profession_id', 0)->whereBetween('date_added', [ $dstart, $dend ]);
            },
            'sendHubProjectSolutionCommentCount' => function ($q) use ($dstart, $dend) {
                $q->where('profession_id', 0)->whereBetween('date_added', [ $dstart, $dend ]);
            },
            'inboxCount'                         => function ($q) use ($dstart, $dend) {
                $q->whereBetween('date_added', [ $dstart, $dend ]);
            },
            'sendUserCommentCount'               => function ($q) use ($dstart, $dend) {
                $q->whereBetween('created_at', [ $dstart, $dend ]);
            }
        ]);
        return $this;
    }

    public function validUpdate($id, $data)
    {
        $rule = [
            'email'        => 'required|email|unique:user,email,' . $id . ',user_id',
            'company_url'  => 'url',
            'personal_url' => 'url'
        ];

        $validator = Validator::make($data, $rule);
        if ($validator->passes()) {
            return true;
        }

        $this->error = $validator;

        return false;
    }

    /**
     * @return \Illuminate\Support\MessageBag
     */
    public function errors()
    {
        return $this->error;
    }

    public function update($id, $data)
    {
        /** @var User $user */
        $user = $this->user->find($id);
        $is_type_change = isset($data['user_type']) && $user->user_type !== $data['user_type'];
        $user->fill(array_only($data, self::$update_columns));

        if (null !== array_get($data, 'head', null)) {
            $user->image = $this->image_uplodaer->uploadUserImage($data['head']);
        }
        //check input user_type exists, not exists don't change user role
        if (array_key_exists('user_type', $data)) {
            $user->is_sign_up_as_expert = 0;
            $user->is_apply_to_be_expert = 0;
        }
        if ($is_type_change) {
            // It means that the user_type change from creator to expert or from expert to creator
            // If the user is from creator to expert than write the expert_approved_at, otherwise clear expert_approved_at
            $user->expert_approved_at = ($user->isExpert()) ? Carbon::now() : null;
        }
        if ($is_type_change && $user->isExpert() &&  $apply_expert_msg = $this->apply_expert_msg_repo->byUserId($user->user_id)->first()) {
            $apply_expert_msg->expired_at = (!is_null($apply_expert_msg->expired_at)) ? $apply_expert_msg->expired_at : Carbon::now();
            $apply_expert_msg->save();
        }
        $user->user_category_id     = implode(',', array_get($data, 'user_category_ids', []));

        $tag_ids    = $user->expertises ? explode(',', $user->expertises) : [];
        $tags = $this->expertise->getDisplayTags($tag_ids);
        if ($tags) {
            $user->tags = implode(',', $tags->toArray());
        }

        $user->save();
    }

    public function changeUserType($id, $user_type)
    {
        $user = $this->user->find($id);
        switch ($user_type) {
            case User::TYPE_CREATOR:
            case User::TYPE_EXPERT:
                $user->user_type             = $user_type;
                $user->is_sign_up_as_expert  = 0;
                $user->is_apply_to_be_expert = 0;
                break;
            case User::TYPE_PM:
                $user->user_type = User::TYPE_PM;
                break;
        }
        $user->save();
    }

    public function findHWTrekPM()
    {
        return $this->user->where('user_type', User::TYPE_PM)->get();
    }

    public function updateInternalMemo($user_id, $data)
    {
        $memo = $this->user_memo->find($user_id);
        if ($memo) {
            $memo->fill(array_only($data, $this->update_memo_columns));
            return $memo->save();
        } else {
            $this->user_memo->id = $user_id;
            $this->user_memo->fill(array_only($data, $this->update_memo_columns));
            return $this->user_memo->save();
        }
    }

    /*
     * @param Paginator|Collection
     * return array
     */
    public function toOutputArray($users)
    {
        return $users->map(
            function ($user) {
                return $this->userOutput($user);
            }
        );
    }

    private function userOutput(User $user)
    {
        return [
            '#'            => $user->user_id,
            'First Name'   => $user->user_name,
            'Last Name'    => $user->last_name,
            'Type'         => $user->textType(),
            'Email'        => $user->email,
            'Country'      => $user->country,
            'City'         => $user->city,
            'Company'      => $user->company,
            'Position'     => $user->business_id,
            'Registed on'  => $user->date_added,
            'Signup ip'    => $user->signup_ip,
            'Email Verify' => $user->textEmailVerify(),
            'Active'       => $user->textActive()
        ];
    }
}
