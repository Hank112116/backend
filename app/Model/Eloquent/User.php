<?php

namespace Backend\Model\Eloquent;

use Config;
use UrlFilter;
use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{

    protected $table = 'user';
    protected $primaryKey = 'user_id';
    protected $appends = ['image_url', 'full_name'];

    public $timestamps = false; // not use created_at, updated_at

    public static $unguarded = true;
    public static $partial = ['user_id', 'user_name', 'last_name', 'email', 'user_type', 'image'];

    const ROLE_MEMBER = 0;
    const ROLE_MANAGER = 1;
    const ROLE_ADMIN = 2;

    private static $roles = [
        self::ROLE_MEMBER  => 'Member',
        self::ROLE_MANAGER => 'Manager',
        self::ROLE_ADMIN   => 'Admin'
    ];

    const TYPE_CREATOR = '0';
    const TYPE_EXPERT = '1';

    private static $types = [
        self::TYPE_CREATOR => 'Creator',
        self::TYPE_EXPERT  => 'Expert',
    ];

    const EMAIL_VERIFY_NONE = 1;
    const EMAIL_VERIFY = 2;
    const EMAIL_VERIFY_BOUNCE = 3;
    const EMAIL_VERIFY_COMPLAIN = 4;

    private static $email_verify_types = [
        self::EMAIL_VERIFY_NONE     => 'Not verify',
        self::EMAIL_VERIFY          => 'Verified',
        self::EMAIL_VERIFY_BOUNCE   => 'Bounce',
        self::EMAIL_VERIFY_COMPLAIN => 'Complain'
    ];

    // active in database may be ''(empty string), 1, 0
    private static $actives = ['0' => 'N', '1' => 'Y'];

    private $expertise_tags = null;

    public function projects()
    {
        return $this->hasMany(Project::class)->orderBy('project_id', 'desc');
    }

    public function solutions()
    {
        return $this->hasMany(Solution::class)->orderBy('solution_id', 'desc');
    }

    public function scopeExpert($query)
    {
        return $query
            ->where('is_hwtrek_pm', '!=', self::IS_HWTREK_PM)
            ->where('user_type', '=', self::TYPE_EXPERT);
    }

    public function scopeCreator($query)
    {
        return $query
            ->where('is_hwtrek_pm', '!=', self::IS_HWTREK_PM)
            ->where('user_type', '=', self::TYPE_CREATOR);
    }

    public function scopePM($query)
    {
        return $query->where('is_hwtrek_pm', true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function backedProducts()
    {
        return $this->hasMany(Transaction::class, 'user_id', 'user_id');
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function verified()
    {
        return $this->email_verify==self::EMAIL_VERIFY;
    }

    public function getImagePath()
    {
        return $this->image ?
            Config::get('s3.thumb') . $this->image : Config::get('s3.default_user');
    }

    public function textType()
    {
        if (!array_key_exists($this->user_type, static::$types)) {
            return 'Undefine';
        }

        return static::$types[$this->user_type];
    }

    public function textRole()
    {
        return static::$roles[$this->user_role];
    }

    public function textFullName()
    {
        return "{$this->user_name} {$this->last_name}";
    }

    public function textFrontLink()
    {
        $name = UrlFilter::filter("{$this->user_name}-{$this->last_name}");
        return 'https://' . Config::get('app.front_domain') . "/profile/{$name}.{$this->user_id}";
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
        if (!array_key_exists($this->active, static::$actives)) {
            return 'unknown';
        } else {
            return static::$actives[$this->active];
        }
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

    public function sendCommentCount()
    {
        return $this->hasOne(Comment::class)->selectRaw('user_id, count(*) as commentCount')->groupBy('user_id');
    }

    public function sendHubCommentCount()
    {
        return $this->hasOne(PmsTempComment::class)->selectRaw('user_id, count(*) as commentCount')->groupBy('user_id');
    }

    public function getSendCommentCountAttribute()
    {
        if (!array_key_exists('sendCommentCount', $this->relations)) {
            $this->load('sendCommentCount');
        }
        if (!array_key_exists('sendHubCommentCount', $this->relations)) {
            $this->load('sendHubCommentCount');
        }
        $commentCount    = $this->getRelation('sendCommentCount');
        $commentCount    = ($commentCount) ? $commentCount->commentCount : 0;
        $hubCommentCount = $this->getRelation('sendHubCommentCount');
        $hubCommentCount = ($hubCommentCount) ? $hubCommentCount->commentCount : 0;
        return $commentCount + $hubCommentCount;
    }

    public function isCreator()
    {
        return $this->user_type == self::TYPE_CREATOR;
    }

    public function isExpert()
    {
        return $this->user_type == self::TYPE_EXPERT;
    }

    public function isToBeExpert()
    {
        return $this->is_sign_up_as_expert;
    }

    public function isHWTrekPM()
    {

        return in_array($this->user_id, [ 6, 126, 128, 1036, 1322, 1545, 2488, 2508, 2569, 2960, 3157 ]);
    }

    public function hasExpertiseTag($tag_id)
    {
        if (is_null($this->expertise_tags)) {
            $this->expertise_tags = $this->expertises ?
                explode(',', $this->expertises) : [];
        }

        return in_array($tag_id, $this->expertise_tags);
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
    public function get100items()
    {
        echo 123;
    }
}
