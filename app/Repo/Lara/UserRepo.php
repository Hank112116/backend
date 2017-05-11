<?php namespace Backend\Repo\Lara;

use Backend\Exceptions\User\CompanyLogoRequiredException;
use ImageUp;
use Validator;
use Carbon\Carbon;
use Backend\Facades\Log;
use Backend\Model\Eloquent\PremiumAccountOrder;
use Backend\Model\Eloquent\Expertise;
use Backend\Api\ApiInterfaces\UserApi\ProfileApiInterface;
use Backend\Model\Eloquent\User;
use Backend\Model\Eloquent\InternalUserMemo;
use Backend\Repo\RepoInterfaces\ApplyExpertMessageInterface;
use Backend\Repo\RepoInterfaces\ExpertiseInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoTrait\PaginateTrait;
use Illuminate\Database\Eloquent\Collection;

class UserRepo implements UserInterface
{
    use PaginateTrait;

    protected $with_relations = ['internalUserMemo', 'applyExpertMessage'];

    private $error;
    private $user;
    private $user_memo;
    private $expertise;
    private $apply_expert_msg_repo;
    private $image_uplodaer;

    private static $update_columns = [
        'active', 'email_verify', 'email',
        'user_name', 'last_name', 'country', 'city',
        'company', 'company_url', 'company_logo', 'personal_url', 'business_id',
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
        ImageUp $image_uploader,
        Expertise $expertise_elq
    ) {
        $this->user                  = $user;
        $this->user_memo             = $user_memo;
        $this->expertise             = $expertise;
        $this->apply_expert_msg_repo = $apply_expert_msg_repo;
        $this->image_uplodaer        = $image_uploader;
        $this->expertise_elo         = $expertise_elq;
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
        return $this->user->where('user_id', $id)
            ->whereIn('user_type', [
                User::TYPE_EXPERT,
                User::TYPE_PREMIUM_EXPERT
            ])
            ->first();
    }

    public function findActiveExpert($id)
    {
        return $this->user->where('user_id', $id)
            ->queryActiveExpert()
            ->first();
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
            ->pluck('user_id');
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

    public function byMail($email)
    {
        return $this->user->where('email', $email)->first();
    }

    public function byLikeMail($email = '')
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
        $users = $this->user->with($this->with_relations)->orderBy('user_id', 'desc');

        if (!empty($input['user_name'])) {
            $user_name = $input['user_name'];
            $users = $users->orWhere('user_name', 'LIKE', "%{$user_name}%")
                           ->orWhere('last_name', 'LIKE', "%{$user_name}%");
        }

        if (!empty($input['user_id'])) {
            $user_id = $input['user_id'];
            $users = $users->where('user_id', $user_id);
        }

        if (!empty($input['email'])) {
            $email = $input['email'];
            $users = $users->where('email', 'LIKE', "%{$email}%");
        }

        if (!empty($input['company'])) {
            $company = $input['company'];
            $users = $users->where('company', 'LIKE', "%{$company}%");
        }

        if (!empty($input['dstart'])) {
            $dstart = $input['dstart'];

            if (!empty($input['dend'])) {
                $dend = Carbon::parse($input['dend'])->addDay()->toDateString();
            } else {
                $dend = Carbon::tomorrow()->toDateString();
            }
            $users = $users->whereBetween('date_added', [$dstart, $dend]);
        }

        if (!empty($input['status'])) {
            if ($input['status'] != 'all') {
                $status = $input['status'];
                switch ($status) {
                    case 'expert':
                        $users = $users->queryExperts();
                        break;
                    case 'creator':
                        $users = $users->queryCreators()
                            ->where('is_sign_up_as_expert', '!=', true)
                            ->where('is_apply_to_be_expert', '!=', true);
                        break;
                    case 'to-be-expert':
                        $users = $users->queryPendingToBeExpert();
                        break;
                    case 'premium-expert':
                        $users = $users->queryPremiumExpert();
                        break;
                    case 'premium-creator':
                        $users = $users->queryPremiumCreator();
                        break;
                    case 'pm':
                        $users = $users->queryPM();
                        break;
                }
            }
        }

        if (!empty($input['description'])) {
            $description = $input['description'];

            $memo = $this->user_memo->select('id')->where('description', 'LIKE', "%{$description}%")->get();

            $users = $users->whereIn('user_id', $memo->pluck('id'));
        }

        if (!empty($input['tag'])) {
            $search_tag = $input['tag'];

            $memo = $this->user_memo->select('id')->where('tags', 'LIKE', "%{$search_tag}%")->get();

            $expertise = $this->expertise_elo->select('expertise_id')->where('tag', 'LIKE', "%{$search_tag}%")->get();

            $users = $users->where(function ($users) use ($memo, $expertise) {
                $users->orWhereIn('user_id', $memo->pluck('id'));
                if (!empty($expertise->implode('expertise_id', ','))) {
                    $users->orWhere('expertises', 'LIKE', "%{$expertise->implode('expertise_id', ',')}%");
                }
            });
        }

        if (!empty($input['action'])) {
            $action = $input['action'];

            $memo = $this->user_memo->select('id')->where('report_action', 'LIKE', "%{$action}%")->get();

            $users = $users->whereIn('user_id', $memo->pluck('id'));
        }
        $total = $users->count();
        $users = $users->skip($per_page * ($page -1))
                       ->take($per_page);

        return $this->getSearchPaginateContainer($total, $per_page, $users->get());
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
                return $user->isBasicExpert();
            }
        );
    }

    public function filterPremiumExperts(Collection $users)
    {
        return $users->filter(
            function (User $user) {
                return $user->isPremiumExpert();
            }
        );
    }

    public function filterCreator(Collection $users)
    {
        return $users->filter(
            function (User $user) {
                return $user->isBasicCreator();
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

    public function filterToBeExpert(Collection $users)
    {
        return $users->filter(
            function (User $user) {
                return $user->isPendingExpert();
            }
        );
    }

    public function filterPremiumCreator(Collection $users)
    {
        return $users->filter(
            function (User $user) {
                return $user->isPremiumCreator();
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
            'personal_url' => 'url',
            'head'         => 'image|max:2048|mimes:jpeg,jpg,png,gif',
            'company_logo' => 'image|max:2048|mimes:jpeg,jpg,png,gif',
        ];

        $validator = Validator::make($data, $rule);

        $validator->setCustomMessages([
            'head.max'           => 'The avatar may not be greater than 2MB.',
            'head.mimes'         => 'The avatar must be a file of type: jpeg, jpg, png, gif.',
            'company_logo.max'   => 'The company logo may not be greater than 2MB.',
            'company_logo.mimes' => 'The company logo must be a file of type: jpeg, jpg, png, gif.',
        ]);

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
        $change_time = Carbon::now();
        /** @var User $user */
        $user = $this->user->find($id);

        $is_type_change = isset($data['user_type']) && $user->user_type !== $data['user_type'];
        if ($is_type_change) {
            if ($data['user_type'] === User::TYPE_PREMIUM_CREATOR or $data['user_type'] === User::TYPE_PREMIUM_EXPERT) {
                if (is_null($user->company_logo) and null === array_get($data, 'company_logo', null)) {
                    throw new CompanyLogoRequiredException('Logo is required for Premium account');
                }
            }

            /* @var ProfileApiInterface  $profile_api */
            $profile_api = app()->make(ProfileApiInterface::class);

            $profile_api->transformAccountType($user, $data['user_type']);
        }

        $user->fill(array_only($data, self::$update_columns));

        if (null !== array_get($data, 'head', null)) {
            $user->image = $this->image_uplodaer->uploadUserImage($data['head']);
        }

        if (array_key_exists('active', $data)) {
            $user->email_verify = $data['active'] ? User::EMAIL_VERIFY : User::EMAIL_VERIFY_NONE;
        }

        $user->user_category_id = implode(',', array_get($data, 'user_category_ids', []));

        $tag_ids = $user->expertises ? explode(',', $user->expertises) : [];
        $tags    = $this->expertise->getDisplayTags($tag_ids);

        if ($tags) {
            $user->tags = implode(',', $tags->toArray());
        }

        $user->setUpdatedAt($change_time);

        $user->save();

        if (null !== array_get($data, 'company_logo', null)) {
            $this->image_uplodaer->uploadCompanyLogo($user, $data['company_logo']);
        }
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
