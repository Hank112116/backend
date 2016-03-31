<?php

namespace Backend\Model\Eloquent;

use Backend\Enums\ProjectCategoryEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as Eloquent;
use FrontLinkGenerator;
use Illuminate\Database\Eloquent\Builder;
use Backend\Model\ModelTrait\TagTrait;
use App;
use Config;
use Carbon;

class Project extends Eloquent
{
    use TagTrait;

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
        'battery' => [
            'capacity' => 0
        ],
        'wireless' => [
            'volt'   => 0,
            'ampere' => 0
        ],
        'other' => 'No other power setting'
    ];

    private $dimension_spec_default = [
        'length' => 0,
        'width'  => 0,
        'height' => 0,
        'other'  => 'No other shape setting'
    ];

    private $weight_spec_default = [
        'weight' => 0,
        'other'  => 'No other weight setting'
    ];

    private $founding_round_default = [
        'rounds'        => null,
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
        return $this->belongsTo(User::class);
    }

    public function lastEditorUser()
    {
        return $this->hasOne(User::class, 'user_id', 'last_editor_id');
    }

    public function projectTeam()
    {
        return $this->hasOne(ProjectTeam::class, 'id', 'project_id');
    }

    public function internalProjectMemo()
    {
        return $this->hasOne(InternalProjectMemo::class, 'id', 'project_id');
    }

    public function categoryData()
    {
        $data['tag_id']   = $this->category_id;
        $data['tag_name'] = ProjectCategoryEnum::CATEGORIES[$this->category_id];
        return (object) $data;
    }


    public function propose()
    {
        return $this->hasMany(ProposeSolution::class, 'project_id', 'project_id');
    }

    public function recommendExperts()
    {
        return $this->hasMany(ProjectMailExpert::class, 'project_id', 'project_id');
    }

    public function projectAttachments()
    {
        return $this->hasMany(ProjectAttachment::class, 'project_id', 'project_id');
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
        return $this->projectTeam->company_name ? $this->projectTeam->company_name : 'N/A';
    }

    public function textCompanyUrl()
    {
        if (!$this->projectTeam) {
            return null;
        }
        return $this->projectTeam->company_url ? $this->projectTeam->company_url : '';
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

    public function textLastUpdateTime()
    {
        $dt = Carbon::parse($this->update_time);
        return $dt->toFormattedDateString();
    }

    public function textSubmitTime()
    {
        if ($this->project_submit_time) {
            return Carbon::parse($this->project_submit_time)->toFormattedDateString();
        } else {
            return Carbon::parse($this->date_added)->toFormattedDateString();
        }
    }

    public function textDeletedTime()
    {
        return Carbon::parse($this->deleted_date)->toFormattedDateString();
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

    public function textTargetMartket()
    {
        $target_martkets = [];
        if ($this->target_market) {
            $target_martkets = json_decode($this->target_markets, true);
        }

        if ($target_martkets) {
            return implode(',', $target_martkets);
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
        $power_spec = array_merge($this->power_spec_default, $power_spec);
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
        if (!$this->internalProjectMemo) {
            return false;
        }
        return $this->internalProjectMemo->hasProjectManager();
    }

    public function fundingRounds()
    {
        $funding_rounds = json_decode($this->funding_status, true);

        if ($funding_rounds) {
            foreach ($funding_rounds as $index => $funding_round) {
                $tmp = array_merge($this->founding_round_default, $funding_round);
                if (array_key_exists('rounds', $tmp)) {
                    $tmp['rounds'] = $this->funding_round_map[$tmp['rounds']];
                } else {
                    $tmp['rounds'] = 'N/A';
                }

                if (!is_null($tmp['date'])) {
                    $tmp['date'] = Carbon::createFromTimestamp($tmp['date'])->toDateString();
                } else {
                    $tmp['date'] = 'N/A';
                }

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

    public function getProjectManagers()
    {
        return $this->internalProjectMemo ? $this->internalProjectMemo->project_managers : null;
    }

    public function getHubManagerNames()
    {
        if (!$this->internalProjectMemo) {
            return [];
        }

        if (!$this->internalProjectMemo->project_managers) {
            return [];
        }
        $managers = json_decode($this->internalProjectMemo->project_managers, true);

        return Adminer::whereIn('hwtrek_member', $managers)->lists('name');
    }

    public function getDeletedHubManagerNames()
    {
        if (!$this->internalProjectMemo) {
            return [];
        }

        if (!$this->internalProjectMemo->project_managers) {
            return [];
        }
        $managers = json_decode($this->internalProjectMemo->project_managers, true);

        return Adminer::onlyTrashed()->whereIn('hwtrek_member', $managers)->lists('name');
    }

    public function recommendExpertTime()
    {
        if ($this->recommendExperts()->getResults()->count() > 0) {
            $recommend_experts = $this->recommendExperts()->getResults();
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

    public function internalProposeSolution()
    {
        return $this->filterProposeSolution(true);
    }

    public function externalProposeSolution()
    {
        return $this->filterProposeSolution(false);
    }

    public function internalRecommendExpert()
    {
        return $this->filterRecommendExpert(true);
    }

    public function externalRecommendExpert()
    {
        return $this->filterRecommendExpert(false);
    }

    private function filterProposeSolution($is_hwtrek_pm)
    {
        $results = $this->propose()->getResults();
        $results = $results->filter(function ($item) use ($is_hwtrek_pm) {
            if ($item->event !== 'click') {
                $user_model = new User();
                $user = $user_model->find($item->proposer_id);
                if ($user->isHWTrekPM() == $is_hwtrek_pm) {
                    $solution_model = new Solution();
                    $solution = $solution_model->find($item->solution_id);
                    $item->solution_url   = $solution->textFrontLink();
                    $item->solution_title = $solution->textTitle();
                    return $item;
                }
            }
        });
        return $results;
    }

    private function filterRecommendExpert($is_hwtrek_pm)
    {
        $results = [];
        $applicant_model = new GroupMemberApplicant();
        $applicants = $applicant_model->all(['user_id', 'apply_date','referral', 'additional_privileges']);
        $project_id = $this->project_id;


        if ($applicants) {
            foreach ($applicants as $applicant) {
                $additional_privileges = json_decode($applicant->additional_privileges, true);
                $flag = false;
                foreach ($additional_privileges as $additional_privilege) {
                    if (in_array('project', $additional_privilege) &&
                        in_array($project_id, $additional_privilege) &&
                        $applicant->referral
                    ) {
                        $flag = true;
                        break;
                    }
                }
                if ($flag) {
                    $user_model = new User();
                    $referral = $user_model->find($applicant->referral);
                    if ($referral->isHWTrekPM() == $is_hwtrek_pm) {
                        $user                 = $user_model->find($applicant->user_id);
                        $data['user_id']      = $applicant->user_id;
                        $data['profile_url']  = $user->textFrontLink();
                        $data['user_name']    = $user->textFullName();
                        $data['company_name'] = $user->company;
                        $data['type']         = 'applicant';
                        $results[]             = $data;
                    }
                }
            }
        }
        if ($is_hwtrek_pm) {
            $recommend_experts = $this->recommendExperts()->getResults();

            if ($recommend_experts) {
                foreach ($recommend_experts as $recommend_expert) {
                    $data['user_id']      = $recommend_expert->expert_id;
                    $data['profile_url']  = $recommend_expert->user->textFrontLink();
                    $data['user_name']    = $recommend_expert->user->textFullName();
                    $data['company_name'] = $recommend_expert->user->company;
                    $data['type']         = 'email-out';
                    $results[]             = $data;
                }
            }
        }
        return Collection::make($results);
    }
}
