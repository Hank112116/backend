<?php namespace Backend\Model;

use Backend\Model\Eloquent\Project;
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

    private $project_profile;
    private $image_uploader;
    private $project;

    public function __construct(
        Project $project,
        ProjectProfile $project_profile,
        ImageUp $image_uploader
    ) {
        $this->project   = $project;
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

    /**
     * @param \Backend\Model\Eloquent\Project|\Backend\Model\Eloquent\DuplicateProject $project
     */
    private function update($project)
    {
        $project->update_time = Carbon::now();
        $project->save();
    }
}
