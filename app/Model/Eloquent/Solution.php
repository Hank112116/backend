<?php

namespace Backend\Model\Eloquent;

use Backend\Enums\DeleteReason;
use Backend\Model\ModelTrait\ProjectTagTrait;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use UrlFilter;

class Solution extends Eloquent
{

    use ProjectTagTrait;

    // 'image_gallery' column json attributes
    const GALLERY_URL = 'fileUrl';
    const GALLERY_FILENAME = 'fileName';
    const GALLERY_DESCRIPTION = 'description';

    // 'customer_portfolio' column json attributes
    const COMPANY_NAME = 'companyName';
    const COMPANY_URL = 'companyUrl';
    const TYPE_SOLUTION = 0;
    const TYPE_PROGRAM = 1;

    private static $types = [
        self::TYPE_SOLUTION => 'Solution',
        self::TYPE_PROGRAM  => 'Program',
    ];
    protected $table = 'solution';
    protected $primaryKey = 'solution_id';

    public $timestamps = false;
    public static $unguarded = true;

    public static $partial = ['solution_id', 'solution_title'];

    private $galleries = null;

    private $category = null;
    private $certification = null;

    private $project_progress = null;
    private $project_category = null;

    public $draft_status = [
        'solution_draft' => 0,
        'active'         => 0,
        'is_deleted'     => 0
    ];

    public $wait_approve_status = [
        'solution_draft'      => 1,
        'active'              => 0,
        'previously_approved' => 0,
        'is_deleted'          => 0
    ];

    public $on_shelf_status = [
        'solution_draft'      => 1,
        'active'              => 1,
        'previously_approved' => 1,
        'is_deleted'          => 0
    ];

    public $off_shelf_status = [
        'solution_draft'      => 1,
        'active'              => 0,
        'previously_approved' => 1,
        'is_deleted'          => 0
    ];

    public $is_solution_status = [
        'is_program'                       => 0,
        'is_manager_upgrade_to_program'    => 0,
        'is_manager_downgrade_to_solution' => 0,
    ];

    public $is_program_status = [
        'is_program'                       => 1,
        'is_manager_upgrade_to_program'    => 0,
        'is_manager_downgrade_to_solution' => 0,
    ];

    public $is_pending_solution_status = [
        'is_program'                       => 1,
        'is_manager_upgrade_to_program'    => 0,
        'is_manager_downgrade_to_solution' => 1,
        'is_deleted'                       => 0
    ];

    public $is_pending_program_status = [
        'is_program'                       => 0,
        'is_manager_upgrade_to_program'    => 1,
        'is_manager_downgrade_to_solution' => 0,
        'is_deleted'                       => 0
    ];

    public $after_submitted_status = ['solution_draft' => '1'];

    private $deleted_reason_map = [
        DeleteReason::BY_OWNER     => 'by owner',
        DeleteReason::BY_BACKEND   => 'by backend',
        DeleteReason::USER_SUSPEND => 'by user suspend'
    ];

    public function id()
    {
        return $this->solution_id;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id')
            ->select([
                'user_id', 'user_name', 'last_name', 'country', 'city',
                'user_type', 'is_sign_up_as_expert', 'is_apply_to_be_expert',
                'country', 'city', 'company', 'email_verify', 'active', 'company_url',
                'suspended_at'
            ]);
    }

    /**
     * Query Draft Solutions
     * @param Builder $query
     * @return mixed
     */
    public function scopeQueryDraft(Builder $query)
    {
        return $query->where($this->draft_status);
    }

    /**
     * Query Approved( On-Shelf & Off-Shelf ), Wait-Approve, Approve-Failed-Draft Solutions
     * @param Builder $query
     * @return mixed
     */
    public function scopeQueryAfterSubmitted(Builder $query)
    {
        return $query->where($this->after_submitted_status);
    }

    /**
     * Query  Wait-Approve
     * @param Builder $query
     * @return mixed
     */
    public function scopeQueryWaitApproved(Builder $query)
    {
        return $query->where($this->wait_approve_status);
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

    /**
     * Query Solution
     * @param Builder $query
     * @return mixed
     */
    public function scopeQuerySolution(Builder $query)
    {
        return $query->where($this->is_solution_status);
    }
    /**
     * Query Program
     * @param Builder $query
     * @return mixed
     */
    public function scopeQueryProgram(Builder $query)
    {
        return $query->where($this->is_program_status);
    }
    /**
     * Query Pending Solution
     * @param Builder $query
     * @return mixed
     */
    public function scopeQueryPendingSolution(Builder $query)
    {
        return $query->where($this->is_pending_solution_status);
    }
    /**
     * Query Pending Program
     * @param Builder $query
     * @return mixed
     */
    public function scopeQueryPendingProgram(Builder $query)
    {
        return $query->where($this->is_pending_program_status);
    }
    /**
     * Query Pending Program
     * @param Builder $query
     * @return mixed
     */
    public function scopeQueryOnShelf(Builder $query)
    {
        return $query->where($this->on_shelf_status);
    }
    /**
     * Query Pending Program
     * @param Builder $query
     * @return mixed
     */
    public function scopeQueryOffShelf(Builder $query)
    {
        return $query->where($this->off_shelf_status);
    }

    public function isDraft()
    {
        return $this->isStatus($this->draft_status);
    }

    public function isWaitApprove()
    {
        return $this->isStatus($this->wait_approve_status);
    }

    public function isOnShelf()
    {
        return $this->isStatus($this->on_shelf_status);
    }

    public function isOffShelf()
    {
        return $this->isStatus($this->off_shelf_status);
    }

    public function isOngoing()
    {
        return $this->isOnShelf() or $this->isOffShelf() and !$this->isDeleted();
    }

    public function isSolution()
    {
        return $this->isStatus($this->is_solution_status);
    }

    public function isProgram()
    {
        return $this->isStatus($this->is_program_status);
    }

    public function isPendingSolution()
    {
        return $this->isStatus($this->is_pending_solution_status);
    }
    
    public function isPendingProgram()
    {
        return $this->isStatus($this->is_pending_program_status);
    }

    public function isDeleted()
    {
        return $this->is_deleted;
    }
    
    
    private function isStatus($status)
    {
        foreach ($status as $key => $status_flag) {
            if ($this->getAttributeValue($key) != $status_flag) {
                return false;
            }
        }

        return true;
    }

    public function initCertification()
    {
        if (!$this->certification) {
            $this->certification = new \Backend\Model\Plain\SolutionCertification();
            $this->certification->parse($this->solution_obtained, $this->solution_obtained_other);
        }
    }

    public function initProjectProgress()
    {
        if (!$this->project_progress) {
            $this->project_progress = new \Backend\Model\Plain\ProjectProgress();

            $this->project_progress->parseSolutionLookingProgress(
                $this->business_prospect_reference,
                $this->business_prospect_reference_other
            );
        }
    }

    public function initProjectCategory()
    {
        if (!$this->project_category) {
            $this->project_category = new ProjectCategory();

            $this->project_category->parseSolutionLookingCategory(
                $this->project_category_co_work,
                $this->project_category_co_work_other
            );
        }
    }

    public function getImagePath()
    {
        return $this->image ?
            config('s3.origin') . $this->image : config('s3.default_solution');
    }

    public function textTitle()
    {
        if (!$this->solution_title) {
            return 'Untitled';
        }

        return UrlFilter::filterNoHyphen($this->solution_title);
    }

    public function textUserName()
    {
        if (!$this->user) {
            return 'User not exist';
        }

        return $this->user->textFullName();
    }

    public function textDeleteReason()
    {
        if (!array_key_exists($this->deleted_reason, $this->deleted_reason_map)) {
            return null;
        }

        return $this->deleted_reason_map[$this->deleted_reason];
    }

    public function getStatus()
    {
        switch (true) {
            case $this->isOffShelf():
                return 'off-shelf';

            case $this->isOnShelf():
                return 'on-shelf';

            case $this->isDeleted():
                return 'archived';

            case $this->isDraft():
                return 'draft';

            case $this->isWaitApprove():
                return 'submitted';

            default:
                return 'undefined';
        }
    }

    public function textStatus()
    {
        switch (true) {
            case $this->isOffShelf():
                return 'Off Shelf';

            case $this->isOnShelf():
                return 'On Shelf';

            case $this->isWaitApprove():
                return 'Pending for approve';

            case $this->isDraft():
                return 'Unfinished Solution';

            case $this->isDeleted():
                $deleted_reason = $this->textDeleteReason();
                return 'Deleted ' . $deleted_reason;

            default:
                return 'N/A';
        }
    }

    public function textFrontLink()
    {
        $name = UrlFilter::filter($this->textTitle());
        return "//" . config('app.front_domain') . "/solution/{$name}.{$this->solution_id}";
    }

    public function textMainCategory()
    {
        if (!$this->category) {
            $this->category = new \Backend\Model\Plain\SolutionCategory();
        }

        return $this->category->textMainCategory($this->solution_type);
    }

    public function textSubCategory()
    {
        if (!$this->category) {
            $this->category = new \Backend\Model\Plain\SolutionCategory();
        }

        return $this->category->textSubCategory($this->solution_type, $this->solution_detail);
    }

    public function getType()
    {
        switch (true) {
            case $this->isSolution():
                return 'normal-solution';

            case $this->isProgram():
                return 'program';

            case $this->isPendingSolution():
                return 'pending-to-normal-solution';

            case $this->isPendingProgram():
                return 'pending-to-program';
            default:
                return 'N/A';
        }
    }

    public function textType()
    {
        switch (true) {
            case $this->isSolution():
                return 'Solution';

            case $this->isProgram():
                return 'Program';

            case $this->isPendingSolution():
                return 'Pending Solution';

            case $this->isPendingProgram():
                return 'Pending Program';
            default:
                return 'N/A';
        }
    }
    
    public function textLastUpdateDate()
    {
        if ($this->update_time) {
            return Carbon::parse($this->update_time)->toFormattedDateString();
        } else {
            return null;
        }
    }
    
    public function textApproveDate()
    {
        if ($this->approve_time and $this->approve_time != '0000-00-00 00:00:00') {
            return Carbon::parse($this->approve_time)->toFormattedDateString();
        } else {
            return null;
        }
    }

    public function textDeletedTime()
    {
        if ($this->deleted_date) {
            return Carbon::parse($this->deleted_date)->toFormattedDateString();
        } else {
            return null;
        }
    }

    public function galleries()
    {
        if (is_null($this->galleries)) {
            $this->galleries = $this->image_gallery ?
                json_decode($this->image_gallery, true) : [];
        }

        return $this->galleries;
    }

    public function originImage($image)
    {
        return config('s3.origin') . $image;
    }

    public function certifications()
    {
        $this->initCertification();

        return $this->certification->logoList();
    }

    public function certificationOthers()
    {
        $this->initCertification();

        return $this->certification->otherList();
    }


    public function hasCertification($certification_id)
    {
        $this->initCertification();

        return $this->certification->contains($certification_id);
    }


    public function lookingProjectProgresses()
    {
        $this->initProjectProgress();

        return $this->project_progress->solutionLookingProgressTexts();
    }

    public function hasProjectProgress($progress_id)
    {
        $this->initProjectProgress();

        return $this->project_progress->solutionContains($progress_id);
    }


    public function lookingProjectCategories()
    {
        $this->initProjectCategory();

        return $this->project_category->solutionLookingCategories();
    }

    public function hasProjectCategory($category_id)
    {
        $this->initProjectCategory();

        return $this->project_category->solutionContains($category_id);
    }

    public function servedCustomers()
    {
        if (mb_strlen($this->customer_portfolio) == 0) {
            return [];
        }

        $result = [];
        foreach (json_decode($this->customer_portfolio, true) as $customer) {
            if (!$customer[self::COMPANY_NAME] and !$customer[self::COMPANY_URL]) {
                continue;
            }

            $result[] = [
                'url'  => e(preg_replace("/(http:\/\/)|(https:\/\/)|(\/\/)/", "", $customer[self::COMPANY_URL])),
                'name' => e($customer[self::COMPANY_NAME] ?: $customer[self::COMPANY_URL])
            ];
        }

        return $result;
    }
}
