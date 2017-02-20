<?php

namespace Backend\Model\Eloquent;

use Backend\Repo\RepoInterfaces\ExpertiseInterface;
use UrlFilter;
use Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{

    protected $table      = 'user';
    protected $primaryKey = 'user_id';
    protected $appends    = ['image_url', 'full_name'];

    public $timestamps = false; // not use created_at, updated_at

    public static $unguarded = true;
    public static $partial   = ['user_id', 'user_name', 'last_name', 'email', 'user_type', 'image'];

    const ROLE_MEMBER  = 0;
    const ROLE_MANAGER = 1;
    const ROLE_ADMIN   = 2;

    private static $roles = [
        self::ROLE_MEMBER  => 'Member',
        self::ROLE_MANAGER => 'Manager',
        self::ROLE_ADMIN   => 'Admin'
    ];

    const TYPE_CREATOR        = 'creator';
    const TYPE_EXPERT         = 'expert';
    const TYPE_PM             = 'pm';
    const TYPE_PREMIUM_EXPERT = 'premium-expert';

    const EMAIL_VERIFY_NONE     = '1';
    const EMAIL_VERIFY          = '2';
    const EMAIL_VERIFY_BOUNCE   = '3';
    const EMAIL_VERIFY_COMPLAIN = '4';

    private static $email_verify_types = [
        self::EMAIL_VERIFY_NONE     => 'Not verify',
        self::EMAIL_VERIFY          => 'Verified',
        self::EMAIL_VERIFY_BOUNCE   => 'Bounce',
        self::EMAIL_VERIFY_COMPLAIN => 'Complain'
    ];

    const IS_CREATOR_STATUS = [
        'user_type'             => self::TYPE_CREATOR
    ];

    const IS_EXPERT_STATUS = [
        'user_type'             => self::TYPE_EXPERT,
        'is_sign_up_as_expert'  => 0,
        'is_apply_to_be_expert' => 0
    ];

    const IS_PENDING_TO_BE_EXPERT_STATUS = [
        'user_type'             => self::TYPE_CREATOR,
        'is_sign_up_as_expert'  => 1,
        'is_apply_to_be_expert' => 0
    ];

    const IS_APPLY_TO_BE_EXPERT_STATUS = [
        'user_type'             => self::TYPE_CREATOR,
        'is_sign_up_as_expert'  => 0,
        'is_apply_to_be_expert' => 1
    ];

    const IS_PREMIUM_EXPERT_STATUS = [
        'user_type'             => self::TYPE_PREMIUM_EXPERT,
        'is_sign_up_as_expert'  => 0,
        'is_apply_to_be_expert' => 0
    ];

    // active in database may be ''(empty string), 1, 0
    private static $actives = ['0' => 'N', '1' => 'Y'];

    private $expertise_tags = null;

    public function id()
    {
        return $this->user_id;
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'user_id', 'user_id')->orderBy('project_id', 'desc');
    }

    public function solutions()
    {
        return $this->hasMany(Solution::class, 'user_id', 'user_id')->orderBy('solution_id', 'desc');
    }

    public function applyExpertMessage()
    {
        return $this->hasMany(ApplyExpertMessage::class, 'user_id', 'user_id')->where('message', '!=', '')->orderBy('id', 'desc');
    }

    public function internalUserMemo()
    {
        return $this->hasOne(InternalUserMemo::class, 'id', 'user_id');
    }

    public function scopeQueryExperts($query)
    {
        return $query->where('user_type', self::TYPE_EXPERT);
    }

    public function scopeQueryCreators($query)
    {
        return $query->where('user_type', self::TYPE_CREATOR);
    }

    public function scopeQueryPM($query)
    {
        return $query->where('user_type', self::TYPE_PM);
    }
    
    public function scopeQueryPremiumExpert($query)
    {
        return $query->where('user_type', self::TYPE_PREMIUM_EXPERT);
    }

    public function scopeQueryPendingToBeExpert($query)
    {
        return $query->where('user_type', self::TYPE_CREATOR)
                     ->where(function ($query) {
                         $query->orWhere('is_sign_up_as_expert', true)
                               ->orWhere('is_apply_to_be_expert', true);
                     });
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function verified()
    {
        return $this->email_verify == self::EMAIL_VERIFY;
    }

    public function getImagePath()
    {
        return $this->image ?
            config('s3.thumb') . $this->image : config('s3.default_user');
    }

    public function textType()
    {
        if ($this->isType(self::IS_EXPERT_STATUS)) {
            return 'Expert';
        } elseif ($this->isType(self::IS_PENDING_TO_BE_EXPERT_STATUS)) {
            return 'Sign up to Be Expert';
        } elseif ($this->isType(self::IS_APPLY_TO_BE_EXPERT_STATUS)) {
            return 'Apply to Be Expert';
        } elseif ($this->isHWTrekPM()) {
            return 'HWTrek PM';
        } elseif ($this->isPremiumExpert()) {
            return 'Premium Expert';
        } elseif ($this->isType(self::IS_CREATOR_STATUS)) {
            return 'Creator';
        } else {
            return 'Undefine';
        }
    }

    public function textRole()
    {
        return static::$roles[$this->user_role];
    }

    public function textFullName()
    {
        return UrlFilter::filterNoHyphen("{$this->user_name} {$this->last_name}");
    }

    public function textFrontLink()
    {
        $name = UrlFilter::filter("{$this->user_name}-{$this->last_name}");
        return 'https://' . config('app.front_domain') . "/profile/{$name}.{$this->user_id}";
    }

    public function textHWTrekPM()
    {
        if (!$this->isHWTrekPM()) {
            return false;
        }
        return 'HWTrek PM';
    }

    public function getIndustryArray()
    {
        return Industry::parseToArray($this->user_category_id);
    }

    public function textEmailVerify()
    {
        if (!array_key_exists($this->email_verify, static::$email_verify_types)) {
            return 'unknown';
        } else {
            return static::$email_verify_types[$this->email_verify];
        }
    }

    public function textActive()
    {
        if ($this->isSuspended()) {
            return 'N';
        }

        if (!array_key_exists($this->active, static::$actives)) {
            return 'unknown';
        } else {
            return static::$actives[$this->active];
        }
    }

    public function textRegistedOn()
    {
        if ($this->date_added) {
            return Carbon::parse($this->date_added)->toFormattedDateString();
        } else {
            return null;
        }
    }
    
    public function textStatus()
    {
        if ($this->isSuspended()) {
            return 'Suspended';
        }

        if (!$this->isActive()) {
            return 'Inactive';
        }

        if (!$this->isEmailVerify()) {
            return 'Not email verify';
        }

        return 'Active';
    }

    public function getCompanyLink()
    {
        return starts_with($this->company_url, 'http') ?
            $this->company_url : "//{$this->company_url}";
    }

    public function getPersonalLink()
    {
        return starts_with($this->personal_url, 'http') ?
            $this->personal_url : "//{$this->personal_url}";
    }

    public function getFullNameAttribute()
    {
        return $this->textFullName();
    }

    public function getImageUrlAttribute()
    {
        return $this->getImagePath();
    }

    public function getSocialAttribute()
    {
        if ($this->fb_uid) {
            return 'facebook';
        }

        if ($this->ln_uid) {
            return 'linkedin';
        }

        return '';
    }

    public function sendProjectSolutionCommentCount()
    {
        return $this->hasOne(Comment::class, 'user_id', 'user_id')->selectRaw('user_id, count(*) as commentCount')->groupBy('user_id');
    }

    public function sendHubProjectSolutionCommentCount()
    {
        return $this->hasOne(PmsTempComment::class, 'user_id', 'user_id')->selectRaw('user_id, count(*) as commentCount')->groupBy('user_id');
    }

    public function sendUserCommentCount()
    {
        return $this->hasOne(NewComment::class, 'poster_id')->selectRaw('poster_id, count(*) as commentCount')->groupBy('poster_id');
    }

    //Use to get comment count which is be filtered or not be filtered
    public function getCommentCountAttribute()
    {
        if (!array_key_exists('sendProjectSolutionCommentCount', $this->relations)) {
            $this->load('sendProjectSolutionCommentCount');
        }
        if (!array_key_exists('sendHubProjectSolutionCommentCount', $this->relations)) {
            $this->load('sendHubProjectSolutionCommentCount');
        }

        if (!array_key_exists('sendUserCommentCount', $this->relations)) {
            $this->load('sendUserCommentCount');
        }

        $commentCount    = $this->getRelation('sendProjectSolutionCommentCount');
        $commentCount    = ($commentCount) ? $commentCount->commentCount : 0;
        $hubCommentCount = $this->getRelation('sendHubProjectSolutionCommentCount');
        $hubCommentCount = ($hubCommentCount) ? $hubCommentCount->commentCount : 0;

        $userCommentCount      = $this->getRelation('sendUserCommentCount');
        $userCommentCount      = ($userCommentCount) ? $userCommentCount->commentCount : 0;
        return $commentCount + $hubCommentCount + $userCommentCount;
    }

    //Use to get total comment count from user register.
    public function getTotalCommentCountAttribute()
    {
        //Reload relation to ignore where condition
        $this->load('sendProjectSolutionCommentCount');
        $this->load('sendHubProjectSolutionCommentCount');
        $this->load('sendUserCommentCount');

        $commentCount    = $this->getRelation('sendProjectSolutionCommentCount');
        $commentCount    = ($commentCount) ? $commentCount->commentCount : 0;
        $hubCommentCount = $this->getRelation('sendHubProjectSolutionCommentCount');
        $hubCommentCount = ($hubCommentCount) ? $hubCommentCount->commentCount : 0;
        $userCommentCount      = $this->getRelation('sendUserCommentCount');
        $userCommentCount      = ($userCommentCount) ? $userCommentCount->commentCount : 0;
        return $commentCount + $hubCommentCount + $userCommentCount;
    }

    public function isCreator()
    {
        return $this->isType(self::IS_CREATOR_STATUS);
    }

    public function isExpert()
    {
        return $this->isType(self::IS_EXPERT_STATUS)
        or $this->isPremiumExpert()
        or $this->isHWTrekPM();
    }

    public function isToBeExpert()
    {
        return $this->isType(self::IS_PENDING_TO_BE_EXPERT_STATUS);
    }

    public function isHWTrekPM()
    {
        return $this->user_type === self::TYPE_PM;
    }

    public function isPremiumExpert()
    {
        return $this->isType(self::IS_PREMIUM_EXPERT_STATUS);
    }

    public function isApplyExpert()
    {
        return $this->isType(self::IS_APPLY_TO_BE_EXPERT_STATUS);
    }

    public function isActive()
    {
        return $this->active and $this->isEmailVerify();
    }

    public function isPendingExpert()
    {
        return $this->isToBeExpert() or $this->isApplyExpert();
    }

    public function isEmailVerify()
    {
        return $this->email_verify == self::EMAIL_VERIFY;
    }

    public function isSuspended()
    {
        if (is_null($this->suspended_at)) {
            return false;
        } else {
            return true;
        }
    }

    public function textSuspendedAt()
    {
        if ($this->suspended_at) {
            return Carbon::parse($this->suspended_at)->toFormattedDateString();
        } else {
            return null;
        }
    }

    private function isType($status)
    {
        foreach ($status as $key => $status_flag) {
            if ($this->getAttributeValue($key) != $status_flag) {
                return false;
            }
        }

        return true;
    }

    public function hasExpertiseTag($tag_id)
    {
        if (is_null($this->expertise_tags)) {
            $this->expertise_tags = $this->expertises ?
                explode(',', $this->expertises) : [];
        }

        return in_array($tag_id, $this->expertise_tags);
    }

    public function getMappingTag($amount = 0)
    {
        if (!$this->expertises) {
            return [];
        }
        $expertise_repo = app()->make(ExpertiseInterface::class);
        $mapping = $expertise_repo->getDisplayTags(explode(',', $this->expertises))->toArray();

        if ($amount === 0) {
            return $mapping;
        } else {
            return array_slice($mapping, 0, $amount);
        }
    }

    public function toBasicArray()
    {
        return [
            'user_id'   => $this->user_id,
            'full_name' => $this->textFullName(),
            'image'     => $this->getImagePath(),
            'link'      => $this->textFrontLink(),
            'company'   => $this->company,
            'position'  => $this->business_id,
            'is_expert' => $this->isExpert()
        ];
    }

    public function toBasicJson()
    {
        return json_encode($this->toBasicArray());
    }
}
