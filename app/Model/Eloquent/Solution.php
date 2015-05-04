<?php

namespace Backend\Model\Eloquent;

use Backend\Model\Eloquent\ProjectCategory;
use Backend\Model\ModelTrait\ProjectTagTrait;
use Config;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Builder;

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
        'solution_draft' => '0',
        'active'         => '0'
    ];

    public $wait_approve_status = [
        'solution_draft'      => '1',
        'active'              => '0',
        'previously_approved' => '0'
    ];

    public $on_shelf_status = [
        'solution_draft'      => '1',
        'active'              => '1',
        'previously_approved' => '1'
    ];

    public $off_shelf_status = [
        'solution_draft'      => '1',
        'active'              => '0',
        'previously_approved' => '1'
    ];

    public $after_submitted_status = ['solution_draft' => '1'];

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
        return $this->isOnShelf() or $this->isOffShelf();
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
            Config::get('s3.origin') . $this->image : Config::get('s3.default_solution');
    }

    public function textTitle()
    {
        if (!$this->solution_title) {
            return 'Untitled';
        }

        return $this->solution_title;
    }

    public function textUserName()
    {
        if (!$this->user) {
            return 'User not exist';
        }

        return $this->user->textFullName();
    }

    public function textStatus()
    {
        switch (true) {
            case $this->isOffShelf()    :
                return 'Off Shelf';

            case $this->isOnShelf()     :
                return 'On Shelf';

            case $this->isWaitApprove() :
                return 'Pending for approve';

            case $this->isDraft()       :
                return 'Unfinished Solution';
            default:
                return 'N/A';
        }
    }

    public function textFrontLink()
    {
        return "//" . Config::get('app.front_domain') . "/solution/{$this->solution_id}";
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
        return Config::get('s3.origin') . $image;
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
