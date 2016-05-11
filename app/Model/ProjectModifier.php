<?php namespace Backend\Model;

use Backend\Model\Eloquent\Project;
use Backend\Model\Eloquent\InternalProjectMemo;
use Backend\Model\Eloquent\ProjectTeam;
use Backend\Model\Eloquent\ProjectManager;
use Backend\Model\Eloquent\Adminer;
use ImageUp;
use Carbon;
use Backend\Model\Plain\ProjectProfile;
use Backend\Model\ModelInterfaces\ProjectModifierInterface;

class ProjectModifier implements ProjectModifierInterface
{
    private $update_columns = [
        'user_id', 'category_id', 'innovation_type',
        'project_title', 'project_summary',
        'progress', 'project_country', 'project_city', 'description',
        'resource', 'resource_other', 'requirement',
        'quantity', 'budget', 'msrp', 'launch_date', 'tags',
        'is_deleted',
    ];

    private $update_memo_columns = [
        'description', 'schedule_note', 'schedule_note_grade',
        'tags', 'report_action'
    ];

    private $update_team_columns = [
        'company_name', 'company_url', 'size', 'strengths'
    ];

    private $project_profile;
    private $image_uploader;
    private $project;
    private $project_memo;
    private $project_team;
    private $project_manager;
    private $adminer;

    public function __construct(
        Project $project,
        ProjectProfile $project_profile,
        ImageUp $image_uploader,
        InternalProjectMemo $project_memo,
        ProjectTeam $project_team,
        ProjectManager $project_manager,
        Adminer $adminer
    ) {
        $this->project         = $project;
        $this->project_profile = $project_profile;
        $this->image_uploader  = $image_uploader;
        $this->project_memo    = $project_memo;
        $this->project_team    = $project_team;
        $this->project_manager = $project_manager;
        $this->adminer         = $adminer;
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

        if (array_key_exists('tags', $data) and $data[ 'tags' ] !== null) {
            $project->tags = json_encode(explode(',', $project->tags));
        }

        if (array_key_exists('resource', $data) and $data[ 'resource' ] !== null) {
            $project->resource = json_encode(explode(',', $project->resource));
        }

        $this->update($project);

        $this->updateProjectTeam($project_id, $data);
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

    public function updateProjectMemo($project_id, $data)
    {
        $memo = $this->project_memo->find($project_id);
        if ($memo) {
            $memo->fill(array_only($data, $this->update_memo_columns));
            return $memo->save();
        } else {
            $this->project_memo->id = $project_id;
            $this->project_memo->fill(array_only($data, $this->update_memo_columns));
            return $this->project_memo->save();
        }
    }

    public function updateProjectTeam($project_id, $data)
    {
        if (array_key_exists('strengths', $data) and $data['strengths'] !== null) {
            $data['strengths'] = json_encode(explode(',', $data['strengths']));
        }

        $team = $this->project_team->find($project_id);
        if ($team) {
            $team->fill(array_only($data, $this->update_team_columns));
            return $team->save();
        } else {
            $this->project_team->id = $project_id;
            $this->project_team->fill(array_only($data, $this->update_team_columns));
            return $this->project_team->save();
        }
    }

    public function updateProjectManager($project_id, $data)
    {
        $managers = $this->project_manager->where('project_id', $project_id)->get();
        if ($managers->count() > 0) {
            foreach ($managers as $manager) {
                $manager->delete();
            }
        }
        $project_managers = json_decode($data['project_managers'], true);

        if ($project_managers) {
            foreach ($project_managers as $manager) {
                $adminer = $this->adminer->where('hwtrek_member', $manager)->first();
                $manager_model = new ProjectManager();
                $manager_model->project_id = $project_id;
                $manager_model->pm_id      = $manager;
                $manager_model->role_id    = $adminer->role_id;
                $manager_model->save();
            }
        }
        return true;
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
