<?php

use Backend\Model\Eloquent\Project;

class ProjectSubmitTimeUpdater extends Seeder
{
    public function run()
    {
        foreach (Project::where('is_project_submitted', 1)->get() as $project) {
            if ($project->project_submit_time === '0000-00-00 00:00:00' or $project->project_submit_time === null) {
                $project->project_submit_time = $project->date_added;
                $project->save();
            }
        }
    }
}
