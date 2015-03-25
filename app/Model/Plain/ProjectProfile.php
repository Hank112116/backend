<?php namespace Backend\Model\Plain;

use Backend\Model\Eloquent\Project;
use Carbon;

class ProjectProfile
{
    public $is_ongoing;
    public $is_postpone;
    public $is_wait_approve_product;
    public $is_wait_approve_ongoing;
    public $is_fund_end;

    public $text_status;
    public $text_project_submit;
    public $text_product_submit;

    /**
     * Project-Status $draft_project, $private_project, $public_project
     * @var array
     */

    public $draft_project = [
        'choose_type'          => Project::CHOOSE_TYPE_PROJECT,
        'public_draft'         => 0,
        'is_project_submitted' => 0,
    ];

    public $private_project = [
        'choose_type'          => Project::CHOOSE_TYPE_PROJECT,
        'public_draft'         => 0,
        'is_project_submitted' => 1,
    ];

    public $public_project = [
        'choose_type'          => Project::CHOOSE_TYPE_PROJECT,
        'public_draft'         => 1,
        'is_project_submitted' => 1,
    ];

    /**
     * Product-Status $draft_product, $wait_approve_product, $ongoing_product
     * @var array
     */

    public $draft_product = [
        'choose_type'   => Project::CHOOSE_TYPE_PRODUCT,
        'active'        => 0,
        'project_draft' => 0,
    ];

    public $wait_approve_product = [
        'choose_type'   => Project::CHOOSE_TYPE_PRODUCT,
        'active'        => 0,
        'project_draft' => 1,
    ];

    public $ongoing_product = [
        'choose_type'   => Project::CHOOSE_TYPE_PRODUCT,
        'active'        => 1,
        'project_draft' => 1,
    ];

    public function setProject(Project $project)
    {
        $this->project = $project;

        $this->is_wait_approve_product = $this->isStatus($this->wait_approve_product);
        $this->is_ongoing  = $this->isStatus($this->ongoing_product);
        $this->is_postpone = $this->isPostPone();
        $this->is_fund_end = $this->isFundEnd();

        $this->text_status = $project->isProject() ?
            $this->textProjectStatus() : $this->textProductStatus();

        $this->text_project_submit = $this->textProjectSubmit();
        $this->text_product_submit = $this->textProductSubmit();
    }

    public function setIsWaitApproveOngoing($is_wait_approve_ongoing)
    {
        $this->is_wait_approve_ongoing = $is_wait_approve_ongoing;
    }

    private function isPostPone()
    {
        return (
            isset($this->project->postpone_time) and
            $this->project->postpone_time != "0000-00-00 00:00:00"
        );
    }

    public function textExpertPerking()
    {
        return  Carbon::parse($this->project->approve_time)->addDay()->toDateString().' ~ '.
                Carbon::parse($this->project->manufactuer_end_date)->toDateString().
                " ( {$this->project->manufactuer_total_days} Days )";
    }

    public function textCustomerPerking()
    {
        return  Carbon::parse($this->project->manufactuer_end_date)->addDay()->toDateString().' ~ '.
                Carbon::parse($this->project->end_date)->toDateString().
                " ( {$this->project->total_days} Days )";
    }

    private function isBetweenExpertFunding()
    {
        $expert_start_date = Carbon::parse($this->project->approve_time)->addDay()->startOfDay();
        $expert_end_date   = Carbon::parse($this->project->end_date)->endOfDay();

        return Carbon::now()->between($expert_start_date, $expert_end_date);
    }

    private function isBetweenCustomerFunding()
    {
        $customer_start_date = Carbon::parse($this->project->manufactuer_end_date)->addDay()->startOfDay();
        $customer_end_date   = Carbon::parse($this->project->end_date)->endOfDay();

        return Carbon::now()->between($customer_start_date, $customer_end_date);
    }

    private function isFundEnd()
    {
        $end_date       = Carbon::parse($this->project->end_date);
        $end_from_today = Carbon::now()->diffInDays($end_date, $abs = false);

        return ($this->is_ongoing and $end_from_today <= 0);
    }

    private function isFundSuccess()
    {
        return ($this->project->amount_get >= $this->project->amount);
    }

    private function isProjectSubmittedPrivate()
    {
        return $this->isStatus($this->private_project);
    }

    private function isProjectSubmittedPublic()
    {
        return $this->isStatus($this->public_project);
    }

    private function isStatus($status_attributes)
    {
        foreach ($status_attributes as $attr => $value) {
            if ($this->project->$attr != $value) {
                return false;
            }
        }

        return true;
    }

    private function textProjectSubmit()
    {
        switch (true) {
        case $this->isProjectSubmittedPublic() :
        case $this->isProjectSubmittedPrivate() :
            return 'Y';

        default :  //   !$project->isProjectSubmitted()
            return 'N';
        }
    }

    private function textProductSubmit()
    {
        switch (true) {
        case $this->isStatus($this->ongoing_product) :
            return 'Y';

        case $this->is_wait_approve_product :
            return 'Pending';

        default : // $project->isProductDraft() :
            return 'N';
        }
    }

    private function textProjectStatus()
    {
        switch (true) {
        case $this->isProjectSubmittedPublic() :
            return 'Expert-Only Project';
        case $this->isProjectSubmittedPrivate() :
            return 'Private Project';
        default :  //   !$project->isProjectSubmitted()
            return 'Unfinished Draft';
        }
    }

    private function textProductStatus()
    {
        switch (true) {
        case $this->isStatus($this->ongoing_product) :
            return $this->textProductOngoingStatus();

        case $this->is_wait_approve_product :
            return 'Waiting for review';

        default : // $project->isProductDraft() :
            return 'Crowdfund Draft';
        }
    }

    private function textProductOngoingStatus()
    {
        switch (true) {
        case $this->is_fund_end and $this->isFundSuccess():
            return 'Funding Successful';

        case $this->is_fund_end and !$this->isFundSuccess():
            return 'Funding Unsuccessful';

        case $this->isBetweenCustomerFunding() :
            return 'Crowdfunding for Customer';

        case $this->isBetweenExpertFunding() :
            return 'Crowdfunding for Expert';

        case $this->is_postpone:
            return 'Postpone';

        default:
            return 'Crowdfunding Campaign';
        }
    }
}
