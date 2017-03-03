<?php

namespace Backend\Model\Eloquent;

use Backend\Enums\ProjectCategoryEnum;
use Backend\Model\ModelTrait\TagTrait;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use FrontLinkGenerator;

class Project extends Eloquent
{
    use TagTrait;

    protected $table = 'project';
    protected $primaryKey = 'project_id';
    protected $dates = [
        'hub_approve_time', 'date_added', 'update_time', 'deleted_date', 'project_submit_time'
    ];

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

    private $quantity_map = [
        '0-500'     => '0 - 500',
        '500-1000'  => '500 - 1,000',
        '1000-2000' => '1,000 - 2,000',
        '2000-5000' => '2,000 - 5,000',
        '5000+'     => '5,000+',
        'not-sure'  => 'Not sure yet'
    ];

    private $team_size_map = [
        '1-5'   => '1 - 5',
        '6-10'  => '6 - 10',
        '11-20' => '11 - 20',
        '20+'   => '20+'
    ];

    private $budget_map = [
        '0-50000'       => 'Up to $50,000',
        '50000-100000'  => '$50,000 - $100,000',
        '100000-200000' => '$100,000 - $200,000',
        '200000-500000' => '$200,000 - $500,000',
        '500000+'       => '$500,000+',
        'not-sure'      => 'Don\'t know yet'
    ];

    private $innovation_type_map = [
        'new-development'                => 'New development',
        'next-generation'                => 'Gen II product (prev. enhancement project)',
        'industrial-design-improvement'  => 'Industrial design improvement',
        'mechanical-improvement'         => 'Mechanical improvement',
        'electronic-improvement'         => 'Electronic improvement',
        'software-improvement'           => 'Software improvement',
        'scale-up'                       => 'Scale up',
        'others'                         => 'Others'
    ];

    private $funding_round_map = [
        'seed'      => 'Seed',
        'series-a'  => 'Series A',
        'series-b'  => 'Series B',
        'series-c'  => 'Series C',
        'series-d'  => 'Series D',
        'ipo'       => 'IPO',
        'other'     => 'Other'
    ];

    private $resource_map = [
        'manufacturing' => 'ODM / OEM / EMS',
        'modules'       => 'Modules',
        'ic-design'     => 'IC / Component design',
        'ee'            => 'Electrical engineering',
        'me'            => 'Mechanical engineering',
        'sd'            => 'Software design',
        'consulting'    => 'Manufacturing consulting',
        'marketing'     => 'Marketing services',
    ];

    private static $options = [
        'note_grade'            => [
            'not-graded' => 'Not graded', 'A' => 'Grade A', 'B' => 'Grade B',
            'C' => 'Grade C ', 'D' => 'Grade D', 'pending' => 'Pending'
        ],
    ];

    private $power_spec_default = [
        'ac' => [
            'volt'   => 0,
            'ampere' => 0
        ],
        'dc' => [
            'volt'   => 0,
            'ampere' => 0,
        ],
        'wireless' => [
            'volt'   => 0,
            'ampere' => 0
        ],
        'battery' => [
            'capacity' => 0
        ],
        'other' => 'Other power options'
    ];

    private $dimension_spec_default = [
        'length' => 0,
        'width'  => 0,
        'height' => 0,
        'other'  => 'Other dimensions'
    ];

    private $weight_spec_default = [
        'weight' => 0,
        'other'  => 'Other weights'
    ];

    private $founding_round_default = [
        'round'         => null,
        'date'          => null,
        'investors'     => 'N/A',
        'url'           => 'N/A',
        'amount'        => 0
    ];

    private $kickstarter_url = 'https://www.kickstarter.com/projects/';
    private $indiegogo_url   = 'https://www.indiegogo.com/projects/';

    private $profile;
    private $progress_obj;
    private $active_resources;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id')
            ->select([
                'user_id', 'user_name', 'last_name', 'user_type',
                'is_sign_up_as_expert', 'is_apply_to_be_expert',
                'company', 'active', 'company_url', 'suspended_at',
                'user_role', 'email_verify'
            ]);
    }

    public function lastEditorUser()
    {
        return $this->hasOne(User::class, 'user_id', 'last_editor_id')
            ->select([
                'user_id', 'user_name', 'last_name', 'user_type',
                'is_sign_up_as_expert', 'is_apply_to_be_expert',
                'company'
            ]);
    }

    public function id()
    {
        return $this->project_id;
    }

    public function projectTeam()
    {
        return $this->hasOne(ProjectTeam::class, 'id', 'project_id');
    }

    public function internalProjectMemo()
    {
        return $this->hasOne(InternalProjectMemo::class, 'id', 'project_id')
            ->select(['id', 'description', 'schedule_note', 'schedule_note_grade', 'tags', 'report_action']);
    }

    public function categoryData()
    {
        $data['tag_id']   = $this->category_id;
        $data['tag_name'] = ProjectCategoryEnum::CATEGORIES[$this->category_id];
        return (object) $data;
    }


    public function propose()
    {
        return $this->hasMany(ProposeSolution::class, 'project_id', 'project_id')->orderBy('propose_time', 'desc');
    }

    public function recommendExperts()
    {
        return $this->hasMany(ProjectMailExpert::class, 'project_id', 'project_id');
    }

    public function projectAttachments()
    {
        return $this->hasMany(ProjectAttachment::class, 'project_id', 'project_id');
    }

    public function group()
    {
        return $this->hasMany(ProjectGroup::class, 'project_id', 'project_id');
    }

    public function projectStatistic()
    {
        return $this->hasOne(ProjectStatistic::class, 'id', 'project_id')->select(['id', 'page_view', 'staff_referral', 'user_referral', 'staff_proposed', 'user_proposed']);
    }

    public function projectMember()
    {
        return $this->hasOne(ProjectMember::class, 'project_id', 'project_id');
    }

    public function projectManager()
    {
        return $this->hasMany(ProjectManager::class, 'project_id', 'project_id');
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

    public function getTeamSizeOptions()
    {
        return $this->team_size_map;
    }

    public function getBudgetOptions()
    {
        return $this->budget_map;
    }

    public function getInnovationOptions()
    {
        return $this->innovation_type_map;
    }

    public function getInternalTags()
    {
        if (!$this->internalProjectMemo) {
            return [];
        }

        if (!$this->internalProjectMemo->tags) {
            return [];
        }

        return explode(',', $this->internalProjectMemo->tags);
    }

    public function getImagePath()
    {
        return $this->image ?
            config('s3.origin') . $this->image : config('s3.default_project');
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
        $category = $this->categoryData();
        if (!$category) {
            return '';
        } else {
            return $category->tag_name;
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

    public function textCompanyName()
    {
        if (!$this->projectTeam) {
            return null;
        }
        return $this->projectTeam->company_name;
    }

    public function textCompanyUrl()
    {
        if (!$this->projectTeam) {
            return null;
        }
        return $this->projectTeam->company_url;
    }

    public function textTeamSize()
    {
        if (!$this->projectTeam) {
            return '1-5';
        }
        return $this->projectTeam->size ? $this->projectTeam->size : '1-5';
    }

    public function textFrontLink()
    {
        return FrontLinkGenerator::project($this->uuid);
    }

    public function textFrontProjectLink()
    {
        return FrontLinkGenerator::project($this->uuid);
    }

    public function textMsrp()
    {
        return $this->msrp ? $this->msrp : 'N/A';
    }

    public function textLaunchDate()
    {
        return $this->isLaunchDateUnsure() ? 'N/A' : $this->launch_date;
    }

    public function textProgress()
    {
        if (!$this->progress_obj) {
            $this->progress_obj = new \Backend\Model\Plain\ProjectProgress();
        }

        return $this->progress_obj->textProgress($this->progress);
    }

    public function textLastUpdateTime()
    {
        return $this->update_time->toFormattedDateString();
    }

    public function textSubmitTime()
    {
        if ($this->project_submit_time) {
            return $this->project_submit_time->toFormattedDateString();
        } else {
            return $this->date_added->toFormattedDateString();
        }
    }

    public function textDeletedTime()
    {
        return $this->deleted_date->toFormattedDateString();
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
        if (!$this->projectTeam) {
            return [];
        }
        $team_strengths = json_decode($this->projectTeam->strengths, true);
        return $team_strengths ? $team_strengths : [];
    }

    public function textTargetMarkets()
    {
        $target_markets = [];
        if ($this->target_markets) {
            $target_markets = json_decode($this->target_markets, true);
        }

        if ($target_markets) {
            return implode(',', $target_markets);
        } else {
            return 'N/A';
        }
    }


    public function textBudget()
    {
        if (!$this->budget or
            !array_key_exists($this->budget, $this->budget_map)
        ) {
            return 'N/A';
        }

        return $this->budget_map[$this->budget];
    }

    public function textInnovationType()
    {
        if (!$this->innovation_type or
            !array_key_exists($this->innovation_type, $this->innovation_type_map)
        ) {
            return 'N/A';
        }

        return $this->innovation_type_map[$this->innovation_type];
    }

    public function textKickstarterLink()
    {
        $crowdfunding_campaigns = json_decode($this->crowdfunding_campaigns, true);

        if (is_null($crowdfunding_campaigns) or !array_key_exists('kickstarter', $crowdfunding_campaigns)) {
            return 'N/A';
        }

        if (empty($crowdfunding_campaigns['kickstarter'])) {
            return 'N/A';
        }

        return link_to($this->kickstarter_url . $crowdfunding_campaigns['kickstarter'], $crowdfunding_campaigns['kickstarter'], ['target' => '_blank']);
    }

    public function textIndiegogoLink()
    {
        $crowdfunding_campaigns = json_decode($this->crowdfunding_campaigns, true);

        if (is_null($crowdfunding_campaigns) or !array_key_exists('indiegogo', $crowdfunding_campaigns)) {
            return 'N/A';
        }

        if (empty($crowdfunding_campaigns['indiegogo'])) {
            return 'N/A';
        }

        return link_to($this->indiegogo_url . $crowdfunding_campaigns['indiegogo'], $crowdfunding_campaigns['indiegogo'], ['target' => '_blank']);
    }


    public function powerSpec()
    {
        $power_spec = json_decode($this->power_spec, true);

        if (!$power_spec) {
            $power_spec = [];
        }
        $power_spec             = array_merge($this->power_spec_default, $power_spec);
        $power_spec['dc']       = array_merge($this->power_spec_default['dc'], $power_spec['dc']);
        $power_spec['ac']       = array_merge($this->power_spec_default['ac'], $power_spec['ac']);
        $power_spec['wireless'] = array_merge($this->power_spec_default['wireless'], $power_spec['wireless']);
        $power_spec['battery']  = array_merge($this->power_spec_default['battery'], $power_spec['battery']);

        return json_decode(json_encode($power_spec));
    }

    public function dimensionSpec()
    {
        $dimension_spec = json_decode($this->dimension_spec, true);

        if (!$dimension_spec) {
            $dimension_spec = [];
        }
        $dimension_spec = array_merge($this->dimension_spec_default, $dimension_spec);

        return (object) $dimension_spec;
    }

    public function weightSpec()
    {
        $weight_spec = json_decode($this->weight_spec, true);

        if (!$weight_spec) {
            $weight_spec = [];
        }
        $weight_spec = array_merge($this->weight_spec_default, $weight_spec);

        return (object) $weight_spec;
    }

    public function hasProjectManager()
    {
        if ($this->projectManager->count() == 0) {
            return false;
        }
        return true;
    }

    public function fundingRounds()
    {
        $funding_rounds = json_decode($this->funding_status, true);

        if ($funding_rounds) {
            foreach ($funding_rounds as $index => $funding_round) {
                $tmp = array_merge($this->founding_round_default, $funding_round);
                if (array_key_exists('round', $tmp) and !is_null($tmp['round'])) {
                    $tmp['round'] = $this->funding_round_map[$tmp['round']];
                } else {
                    $tmp['round'] = 'N/A';
                }

                $tmp['date'] = is_null($tmp['date']) ? 'N/A' : $tmp['date'];

                $funding_rounds[$index] = $tmp;
            }
        }
        return json_decode(json_encode($funding_rounds));
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
        $resources = json_decode($this->resource, true);
        if (!$resources and !$this->resource_other) {
            return [];
        }
        $result = [];

        if ($resources) {
            foreach ($resources as $res) {
                if (array_key_exists($res, $this->resource_map)) {
                    $result[] = $this->resource_map[$res];
                }
            }
        }
        if ($this->resource_other) {
            $result[] = $this->resource_other;
        }

        return $result;
    }

    public function isMsrpUnsure()
    {
        return $this->msrp == self::MSRP_UNSURE;
    }

    public function isLaunchDateUnsure()
    {
        return !$this->launch_date;
    }

    /**
     * @return \Backend\Model\Plain\ProjectProfile
     */
    public function profile()
    {
        return $this->getProfileAttribute();
    }

    /**
     * @return \Backend\Model\Plain\ProjectProfile
     */
    public function getProfileAttribute()
    {
        if (!array_key_exists('profile', $this->attributes)) {
            $this->profile = app()->make('Backend\Model\ModelInterfaces\ProjectProfileGeneratorInterface')->gen($this);
        }

        return $this->profile; //$this->attributes['profile'];
    }

    public function getActiveResourcesAttribute()
    {
        if (!isset($this->active_resources)) {
            if (json_decode($this->resource)) {
                $this->active_resources = json_decode($this->resource);
            } else {
                $this->active_resources = [];
            }
        }

        return $this->active_resources;
    }

    public function hasResource($resource_id)
    {
        $active_resources = [];

        if ($this->getActiveResourcesAttribute()) {
            $active_resources = $this->getActiveResourcesAttribute();
        }
        return in_array($resource_id, $active_resources);
    }

    public function textScheduleFrontEditLink()
    {
        return "//" . config('app.front_domain') . "/hub/manage-schedule-panel/{$this->project_id}/admin-edit";
    }

    public function textProjectManagers()
    {
        $r = [];
        if ($this->projectManager->count() > 0) {
            foreach ($this->projectManager as $manager) {
                $r[] = $manager->pm_id;
            }
            return implode(',', $r);
        }
        return null;
    }

    public function getProjectManagers()
    {
        $r = [];
        if ($this->projectManager->count() > 0) {
            foreach ($this->projectManager as $manager) {
                $r[] = $manager->pm_id;
            }
        }
        return json_encode($r);
    }

    public function getHubManagerNames()
    {
        if ($this->projectManager->count() == 0) {
            return [];
        }

        $managers = [];
        foreach ($this->projectManager as $manager) {
            $managers[] = $manager->pm_id;
        }

        return Adminer::whereIn('hwtrek_member', $managers)->pluck('name');
    }

    public function getDeletedHubManagerNames()
    {
        if ($this->projectManager->count() == 0) {
            return [];
        }
        $managers = [];
        foreach ($this->projectManager as $manager) {
            $managers[] = $manager->pm_id;
        }

        return Adminer::onlyTrashed()->whereIn('hwtrek_member', $managers)->pluck('name');
    }

    public function recommendExpertTime()
    {
        if ($this->recommendExperts->count() > 0) {
            $recommend_experts = $this->recommendExperts;
            return Carbon::parse($recommend_experts[0]->date_send)->toFormattedDateString();
        }
        return null;
    }

    public function textNoteGrade()
    {
        return static::$options['note_grade'][$this->internalProjectMemo->schedule_note_grade];
    }

    public function isDeleted()
    {
        return $this->is_deleted;
    }

    public function getPageViewCount()
    {
        if ($this->projectStatistic) {
            return $this->projectStatistic->page_view;
        }
        return 0;
    }

    public function getStaffReferredCount()
    {
        if ($this->projectStatistic) {
            return $this->projectStatistic->staff_referral;
        }
        return 0;
    }

    public function getCollaboratorsCount()
    {
        return $this->projectMember()->count();
    }
    
    public function getStatistic()
    {
        if ($this->projectStatistic) {
            return $this->projectStatistic;
        } else {
            return (object) ProjectStatistic::DEFAULT_STATISTIC;
        }
    }
    
    public function getEmailOutCount()
    {
        if ($this->recommendExperts) {
            return $this->recommendExperts->count();
        } else {
            return 0;
        }
    }

    /**
     * Query Draft Projects
     * @param Builder $query
     * @return mixed
     */
    public function scopeQueryDraft(Builder $query)
    {
        return $query->where($this->profile()->draft_project);
    }

    /**
     * Query public Projects
     * @param Builder $query
     * @return mixed
     */
    public function scopeQueryPublic(Builder $query)
    {
        return $query->where($this->profile()->public_project);
    }

    /**
     * Query private Projects
     * @param Builder $query
     * @return mixed
     */
    public function scopeQueryPrivate(Builder $query)
    {
        return $query->where($this->profile()->private_project);
    }

    /**
     * Query approved schedule Projects
     * @param Builder $query
     * @return mixed
     */
    public function scopeQueryApprovedSchedule(Builder $query)
    {
        return $query->where('hub_approve', 1)
            ->where('is_deleted', 0)
            ->where('is_project_submitted', 1)
            ->where('date_added', '>', env('SHOW_DATE'))
            ->with('recommendExperts');
    }
}
