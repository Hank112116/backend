<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\User;
use ImageUp;
use Validator;
use Carbon;
use Illuminate\Support\Collection;
use Backend\Repo\RepoInterfaces\ExpertiseInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoTrait\PaginateTrait;

class UserRepo implements UserInterface
{

    use PaginateTrait;

    private $error;
    private $user;
    private $rule = ['email' => 'required|email|unique:user'];

    private static $update_columns = [
        'active', 'email_verify', 'user_type', 'email',
        'user_name', 'last_name', 'country', 'city',
        'company', 'company_url', 'personal_url', 'business_id',
        'user_about', 'expertises',
    ];

    public function __construct(
        User $user,
        ExpertiseInterface $expertise,
        ImageUp $image_uploader
    ) {
        $this->user           = $user;
        $this->expertise      = $expertise;
        $this->image_uplodaer = $image_uploader;
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
            'projects.category',
            'solutions',
            'solutions',
            'backedProducts',
            'backedProducts.project'
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
            ->where('is_sign_up_as_expert', '1')
            ->where('user_type', User::TYPE_CREATOR)
            ->lists('user_id');
    }

    public function byPage($page = 1, $limit = 20)
    {
        $users = $this->modelBuilder($this->user, $page, $limit)->get();
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
            'sendCommentCount'    => function ($q) use ($dstart, $dend) {
                $q->whereBetween('date_added', [ $dstart, $dend ]);
            },
            'sendHubCommentCount' => function ($q) use ($dstart, $dend) {
                $q->whereBetween('date_added', [ $dstart, $dend ]);
            },
            'inboxCount'          => function ($q) use ($dstart, $dend) {
                $q->whereBetween('date_added', [ $dstart, $dend ]);
            },
        ]);
        return $this;
    }

    public function filterCommentCountNotZero(Collection $userWithComment)
    {
        return $userWithComment->filter(
            function (User $user) {
                return $user->commentCount != 0;
            }
        );
    }

    public function validUpdate($id, $data)
    {
        $user = $this->user->find($id);
        if ($user->email === $data['email']) {
            return true;
        }

        $validator = Validator::make($data, $this->rule);

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
        $user = $this->user->find($id);
        $user->fill(array_only($data, self::$update_columns));

        if (null !== array_get($data, 'head', null)) {
            $user->image = $this->image_uplodaer->uploadUserImage($data['head']);
        }

        $user->is_sign_up_as_expert = 0;
        $user->user_category_id     = implode(',', array_get($data, 'user_category_ids', []));

        $tag_ids    = $user->expertises ? explode(',', $user->expertises) : [];
        $tags = $this->expertise->getDisplayTags($tag_ids);
        if ($tags) {
            $user->tags = implode(',', $tags->toArray());
        }

        $user->save();
    }

    public function changeHWTrekPM($id, $is_hwtrek_pm)
    {
        $user = $this->user->find($id);
        $user->is_hwtrek_pm = $is_hwtrek_pm;
        $user->save();
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
