<?php namespace Backend\Model;

use Backend\Model\Eloquent\Project;
use Backend\Model\Eloquent\DuplicateProject;
use ImageUp;
use Carbon;
use Backend\Model\Plain\ProjectProfile;
use Backend\Model\ModelInterfaces\ProjectModifierInterface;

class ProjectModifier implements ProjectModifierInterface
{
    private $update_columns = [
        'user_id', 'category_id',
        'project_title', 'project_summary',
        'progress', 'project_country', 'project_city',
        'preliminary_spec', 'key_component', 'description',
        'team', 'resource', 'resource_other', 'requirement',
        'quantity', 'msrp', 'launch_date', 'tags',
        'is_deleted',
    ];

    private $copy_duplicate_columns = [
        'category_id', 'project_title', 'image',
        'project_summary', 'description',
        'project_country', 'project_city',
        'video', 'amount', 'update_time',
    ];

    private $project_profile;
    private $image_uploader;

    public function __construct(
        Project $project,
        DuplicateProject $duplicate,
        ProjectProfile $project_profile,
        ImageUp $image_uploader
    ) {
        $this->project   = $project;
        $this->duplicate = $duplicate;

        $this->project_profile = $project_profile;
        $this->image_uploader  = $image_uploader;
    }

    public function updateProject($project_id, $data)
    {
        $project = $this->project->find($project_id);
        $project->fill(array_only($data, $this->update_columns));

        if ($project->msrp == '') {
            $project->msrp = Project::MSRP_UNSURE;
        }

        if ($project->launch_date == '') {
            $project->launch_date = null;
        }

        if (array_key_exists('cover', $data) and $data[ 'cover' ] !== null) {
            $project->image = $this->image_uploader->uploadImage($data[ 'cover' ]);
        }

        if ($project->is_deleted) {
            $project->deleted_date = Carbon::now();
        }

        $this->update($project);
    }

    public function toDraftProject($project_id)
    {
        $this->project->where('project_id', $project_id)
            ->update($this->project_profile->draft_project);
    }

    public function toSubmittedPrivateProject($project_id)
    {
        $this->project->where('project_id', $project_id)
            ->update($this->project_profile->private_project);
    }

    public function toSubmittedPublicProject($project_id)
    {
        $this->project->where('project_id', $project_id)
            ->update($this->project_profile->public_project);
    }

    public function approveProduct($project_id)
    {
        $project = $this->project->find($project_id);
        $project->fill($this->project_profile->ongoing_product);

        $expert_order_days = $project->manufactuer_total_days + 1;
        $total_order_days  = $expert_order_days + $project->total_days;

        $project->manufactuer_end_date = Carbon::today()->addDays($expert_order_days)->endOfDay();
        $project->end_date             = Carbon::today()->addDays($total_order_days)->endOfDay();

        $project->approve_time = Carbon::now();

        $this->update($project);
    }

    public function rejectProduct($project_id)
    {
        $project = $this->project->find($project_id);
        $project->fill($this->project_profile->draft_product);
        $this->update($project);
    }

    public function updateProduct($project_id, $data)
    {
        $project = $this->project->find($project_id);
        $project->fill(array_except($data, ['_token', 'perks', 'approve', 'cover']));

        if ($data[ 'cover' ]) {
            $project->image = $this->image_uploader->uploadImage($data[ 'cover' ]);
        }

        $this->update($project);
    }

    public function postponeProduct($project_id)
    {
        $this->project
            ->where('project_id', $project_id)
            ->update(['postpone_time' => Carbon::now()]);
    }

    public function recoverPostponeProduct($project_id)
    {
        $project = $this->project->find($project_id);

        $postpone_date   = Carbon::parse($project->postpone_time)->startOfDay();
        $expert_end_date = Carbon::parse($project->manufactuer_end_date)->startOfDay();
        $custom_end_date = Carbon::parse($project->end_date)->startOfDay();

        $postpone_to_expert = $postpone_date->diffInDays($expert_end_date, $abs = false);
        $postpone_to_custom = $postpone_date->diffInDays($custom_end_date, $abs = false);

        if (!$project->profile->is_fund_end) {
            // Expert only period is over
            if ($postpone_to_expert > 0) {
                $project->manufactuer_end_date = Carbon::now()->addDays($postpone_to_expert);
            }

            $project->end_date = Carbon::now()->addDays($postpone_to_custom);
        }

        $project->postpone_time = '0000-00-00 00:00:00';

        $this->update($project);
    }

    public function approveDuplicateProduct($project_id)
    {
        $duplicate = $this->duplicate->find($project_id);
        $data      = array_only($duplicate->toArray(), $this->copy_duplicate_columns);
        $duplicate->delete();

        $project = $this->project->find($project_id);
        $project->fill($data);
        $this->update($project);
    }

    public function rejectDuplicateProduct($project_id)
    {
        $this->duplicate->where('project_id', $project_id)->delete();
    }

    public function updateDuplicateProduct($project_id, $data)
    {
        $duplicate = $this->duplicate->find($project_id);
        $duplicate->fill(array_except($data, ['_token', 'perks', 'approve', 'cover']));

        if ($data[ 'cover' ]) {
            $duplicate->image = $this->image_uploader->uploadImage($data[ 'cover' ]);
        }

        $this->update($duplicate);
    }

    /**
     * @param \Backend\Model\Eloquent\Project|\Backend\Model\Eloquent\DuplicateProject $project
     */
    private function update($project)
    {
        $project->update_time = Carbon::now();
        $project->save();
    }
}
