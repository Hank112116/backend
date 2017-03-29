<?php namespace Backend\Model\Plain;

use Backend\Enums\DeleteReason;
use Backend\Model\Eloquent\Project;
use Carbon\Carbon;

class ProjectProfile
{
    public $is_ongoing;
    public $is_postpone;
    public $is_wait_approve_ongoing;
    public $is_fund_end;

    public $text_status;
    public $text_project_submit;

    private $project;

    /**
     * Project-Status $draft_project, $private_project, $public_project
     * @var array
     */

    public $draft_project = [
        'public_draft'         => 0,
        'is_project_submitted' => 0,
        'is_deleted'           => 0
    ];

    public $private_project = [
        'public_draft'         => 0,
        'is_project_submitted' => 1,
        'is_deleted'           => 0
    ];

    public $public_project = [
        'public_draft'         => 1,
        'is_project_submitted' => 1,
        'is_deleted'           => 0
    ];

    public $ongoing_project = [
        'is_project_submitted' => 1,
        'is_deleted'           => 0
    ];

    private $deleted_reason_map = [
        DeleteReason::BY_OWNER     => 'by owner',
        DeleteReason::BY_BACKEND   => 'by backend',
        DeleteReason::USER_SUSPEND => 'by user suspend'
    ];

    public function setProject(Project $project)
    {
        $this->project = $project;

        $this->is_ongoing  = $this->isStatus($this->ongoing_project);
        $this->is_postpone = $this->isPostPone();
        $this->is_fund_end = $this->isFundEnd();

        $this->text_status = $this->textProjectStatus();

        $this->text_project_submit = $this->textProjectSubmit();
    }

    public function setIsWaitApproveOngoing($is_wait_approve_ongoing)
    {
        $this->is_wait_approve_ongoing = $is_wait_approve_ongoing;
    }

    public function isPublic()
    {
        return $this->isStatus($this->public_project);
    }

    public function isPrivate()
    {
        return $this->isStatus($this->private_project);
    }

    public function isDraft()
    {
        return $this->isStatus($this->draft_project);
    }

    public function isDeleted()
    {
        return $this->project->is_deleted;
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

    public function textDeleteReason()
    {
        if (!array_key_exists($this->project->deleted_reason, $this->deleted_reason_map)) {
            return null;
        }

        return $this->deleted_reason_map[$this->project->deleted_reason];
    }

    private function isFundEnd()
    {
        $end_date       = Carbon::parse($this->project->end_date);
        $end_from_today = Carbon::now()->diffInDays($end_date, $abs = false);

        return ($this->is_ongoing and $end_from_today <= 0);
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
            case $this->isProjectSubmittedPublic():
            case $this->isProjectSubmittedPrivate():
                return 'Y';

            default:  //   !$project->isProjectSubmitted()
                return 'N';
        }
    }

    private function textProjectStatus()
    {
        switch (true) {
            case $this->isProjectSubmittedPublic():
                return 'Expert Mode';
            case $this->isProjectSubmittedPrivate():
                return 'Private Mode';
            case $this->isDraft():
                return 'Unfinished Draft';
            case $this->isDeleted():
                $deleted_reason = $this->textDeleteReason();
                return 'Deleted ' . $deleted_reason;
            default:  //   !$project->isProjectSubmitted()
                return 'N/A';
        }
    }
}
