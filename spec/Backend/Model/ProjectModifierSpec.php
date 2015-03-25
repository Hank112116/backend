<?php

namespace spec\Backend\Model;

use PhpSpec\Laravel\LaravelObjectBehavior;
use Prophecy\Argument;

use Backend\Model\Eloquent\Project, Backend\Model\Eloquent\DuplicateProject;
use Backend\Model\Plain\ProjectProfile;
use ImageUp;

class ProjectModifierSpec extends LaravelObjectBehavior
{
    function let(
        Project $project,
        DuplicateProject $duplicate,
        ProjectProfile $profile,
        ImageUp $image_uploader
    ) {
        $this->beConstructedWith($project, $duplicate, $profile, $image_uploader);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Backend\Model\ProjectModifier');
    }

    function it_change_project_to_draft_status(Project $project)
    {
        $project_id = '0';

        $project->where('project_id', $project_id)->willReturn($project);
        $project->update([])->shouldBeCalled();

        $this->toDraftProject($project_id);
    }
}
