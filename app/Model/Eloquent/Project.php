<?php

namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;
use FrontLinkGenerator;
use Illuminate\Database\Eloquent\Builder;
use Backend\Model\ModelTrait\ProjectTagTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

use App;
use Config;

class Project extends Eloquent
{

    use ProjectTagTrait;

    protected $table = 'project';
    protected $primaryKey = 'project_id';

    public $timestamps = false;
    public static $unguarded = true;

    public static $partial = ['project_id', 'project_title'];

    const MSRP_UNSURE = - 1;

    const PROGRESS_DEFAULT = - 1;

    const PROGRESS_BRINSTROMING = 1;
    const PROGRESS_POC = 2;
    const PROGRESS_PROTOTYPE = 3;
    const PROGRESS_INDUSTRIAL_DESIGN = 4;
    const PROGRESS_IMPROVING = 5;
    const PROGRESS_MANUFACTURABILITY = 6;

    private $current_stage_map = [
        self::PROGRESS_BRINSTROMING      => 'Brainstorming an idea',
        self::PROGRESS_POC               => 'Proof of concept',
        self::PROGRESS_PROTOTYPE         => 'Working prototype',
        self::PROGRESS_INDUSTRIAL_DESIGN => 'Enhancing industrial design',
        self::PROGRESS_IMPROVING         => 'Improving electronic board',
        self::PROGRESS_MANUFACTURABILITY => 'Design for manufacturability'
    ];

    const QUANTITY_0 = 1;
    const QUANTITY_500 = 2;
    const QUANTITY_1000 = 3;
    const QUANTITY_2000 = 4;
    const QUANTITY_5000 = 5;
    const QUANTITY_SURE = 6;

    private $quantity_map = [
        self::QUANTITY_0    => '0 - 500',
        self::QUANTITY_500  => '500 - 1,000',
        self::QUANTITY_1000 => '1,000 - 2,000',
        self::QUANTITY_2000 => '2,000 - 5,000',
        self::QUANTITY_5000 => '5,000+',
        self::QUANTITY_SURE => 'Not sure yet'
    ];

    const RESOURCE_DEM_OEM_EMS = 1;
    const RESOURCE_MODULES = 2;
    const RESOURCE_IC_DESIGN = 3;
    const RESOURCE_EE = 4;
    const RESOURCE_ME = 5;
    const RESOURCE_SD = 6;
    const RESOURCE_CONSULT = 7;
    const RESOURCE_MARKET = 8;

    private $resource_map = [
        self::RESOURCE_DEM_OEM_EMS => 'ODM / OEM / EMS',
        self::RESOURCE_MODULES     => 'Modules',
        self::RESOURCE_IC_DESIGN   => 'IC / Component design',
        self::RESOURCE_EE          => 'Electrical engineering',
        self::RESOURCE_ME          => 'Mechanical engineering',
        self::RESOURCE_SD          => 'Software design',
        self::RESOURCE_CONSULT     => 'Manufacturing consulting',
        self::RESOURCE_MARKET      => 'Marketing services',
    ];

    private $profile;
    private $progress_obj;
    private $active_resources;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id', 'tag_id');
    }


    public function propose()
    {
        return $this->hasMany(ProposeSolution::class, 'project_id', 'project_id');
    }

    // pending-for-approve products
    public function scopeQueryApprovePending(Builder $query)
    {
        return $query->where('active', '0')
            ->where('project_draft', '1');
    }

    /**
     * Query Deleted Solutions
     * @param Builder $query
     * @return mixed
     */
    public function scopeQueryNotDeleted(Builder $query)
    {
        return $query->where('is_deleted', 0);
    }

    /**
     * Query Deleted Solutions
     * @param Builder $query
     * @return mixed
     */
    public function scopeQueryDeleted(Builder $query)
    {
        return $query->where('is_deleted', 1);
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function getCurrentStageOptions()
    {
        return $this->current_stage_map;
    }

    public function getResourceOptions()
    {
        return $this->resource_map;
    }

    public function getQuantityOptions()
    {
        return $this->quantity_map;
    }

    public function getImagePath()
    {
        return $this->image ?
            Config::get('s3.origin') . $this->image : Config::get('s3.default_project');
    }

    public function textChooseType()
    {
        return 'Project';
    }

    public function textVideo()
    {
        if (!$this->video) {
            return '';
        }
        /**
         * parse_url() : gen array [scheme, host, port, user, pass, path, query, fragment]
         * parse_str() : turn key=value to ${key_name} = value
         **/
        $url = parse_url($this->video);
        switch ($url['host']) {
            case 'www.youtube.com':
                $v = ''; // parse_str will set $v
                parse_str($url['query']); // ex: https://www.youtube.com/watch?v=p0yfv7A25a4
                return "//www.youtube.com/embed/{$v}";
            case 'youtu.be':
                return "//www.youtube.com/embed{$url['path']}";
            case 'vimeo.com':
                return "//player.vimeo.com/video{$url['path']}";
            default:
                return '';
        }
    }

    public function textTitle()
    {
        if (!$this->project_title) {
            return 'Untitled';
        } else {
            return $this->project_title;
        }
    }

    public function textCategory()
    {
        if (!$this->category) {
            return '';
        } else {
            return $this->category->tag_name;
        }
    }

    public function textUserName()
    {
        if (!$this->user) {
            return 'User not exist';
        } else {
            return $this->user->textFullName();
        }
    }

    public function textFrontLink()
    {
        return FrontLinkGenerator::project($this->project_id);
    }

    public function textFrontProjectLink()
    {
        return FrontLinkGenerator::project($this->project_id);
    }

    public function textMsrp()
    {
        return $this->isMsrpUnsure() ? 'Unsure' : $this->msrp;
    }

    public function textLaunchDate()
    {
        return $this->isLaunchDateUnsure() ? 'Unsure' : $this->launch_date;
    }

    public function textProgress()
    {
        if (!$this->progress_obj) {
            $this->progress_obj = new \Backend\Model\Plain\ProjectProgress();
        }

        return $this->progress_obj->textProgress($this->progress);
    }

    public function keyComponents()
    {
        if (!$this->key_component) {
            return [];
        }

        return explode(',', $this->key_component);
    }

    public function teamStrengths()
    {
        if (!$this->team) {
            return [];
        }

        return explode(',', $this->team);
    }

    public function firstBatchQuantity()
    {
        if (!$this->quantity or
            !array_key_exists($this->quantity, $this->quantity_map)
        ) {
            return 'N/A';
        }

        return $this->quantity_map[$this->quantity];
    }

    public function resourceRequirements()
    {
        if (!$this->resource and !$this->resource_other) {
            return [];
        }

        $resources = [];
        foreach (explode(',', $this->resource) as $res) {
            if (array_key_exists($res, $this->resource_map)) {
                $resources[] = $this->resource_map[$res];
            }
        }

        if ($this->resource_other) {
            $resources[] = $this->resource_other;
        }

        return $resources;
    }

    public function isMsrpUnsure()
    {
        return $this->msrp == self::MSRP_UNSURE;
    }

    public function isLaunchDateUnsure()
    {
        return !$this->launch_date;
    }

    /*
     * return Backend\Model\Plain\ProjectProfile
     */
    public function getProfileAttribute()
    {
        if (!array_key_exists('profile', $this->attributes)) {
            $this->profile = App::make('Backend\Model\ModelInterfaces\ProjectProfileGeneratorInterface')->gen($this);
        }

        return $this->profile; //$this->attributes['profile'];
    }

    public function getActiveResourcesAttribute()
    {
        if (!isset($this->active_resources)) {
            $this->active_resources = $this->resource ? explode(',', $this->resource) : [];
        }

        return $this->active_resources;
    }

    public function hasResource($resource_id)
    {
        return in_array($resource_id, $this->getActiveResourcesAttribute());
    }
}
