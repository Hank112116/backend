<?php

use Backend\Model\Eloquent\DuplicateProject;
use Backend\Model\Eloquent\Project;
use Carbon\Carbon;
use Laracasts\TestDummy\Factory;
use Backend\Model\Eloquent;

class ProjectModifierTest extends BackendTestCase
{
    private $projects;
    private $profile;
    private $modifier;
    private $image_uploader;

    public function setUp()
    {
        parent::setUp();

        $this->projects       = Factory::times(3)->create(Eloquent\Project::class);
        $this->profile        = new Backend\Model\Plain\ProjectProfile;
        $this->image_uploader = Mockery::mock('ImageUp');

        $this->modifier = new Backend\Model\ProjectModifier(
            new Project,
            new DuplicateProject,
            $this->profile,
            $this->image_uploader
        );
    }

    private function assetEqualToAttributes($mock, $attrs)
    {
        foreach ($attrs as $attr => $value) {
            $this->assertEquals($value, $mock->$attr);
        }
    }

    /** @test */
    public function it_update_image_if_data_contains_cover()
    {
        $file    = Mockery::mock('Symfony\Component\HttpFoundation\File\UploadedFile');
        $project = $this->projects->first();
        $data    = ['cover' => $file];

        $this->image_uploader->shouldReceive('uploadImage')
            ->with($data['cover'])
            ->andReturn('new-image-name');

        $this->modifier->updateProject($project->project_id, $data);
        $this->assertEquals(
            'new-image-name',
            Project::find($project->project_id)->image
        );

        $this->modifier->updateProduct($project->project_id, $data);
        $this->assertEquals(
            'new-image-name',
            Project::find($project->project_id)->image
        );

        $duplicate = Factory::create(Eloquent\DuplicateProject::class);
        $this->modifier->updateDuplicateProduct($duplicate->project_id, $data);
        $this->assertEquals(
            'new-image-name',
            DuplicateProject::find($duplicate->project_id)->image
        );
    }

    /** @test */
    public function it_change_to_draft_after_set_to_draft()
    {
        $project = $this->projects->first();
        $this->modifier->toDraftProject($project->project_id);

        $this->assetEqualToAttributes(
            Project::find($project->project_id),
            $this->profile->draft_project
        );
    }

    /** @test */
    public function it_change_to_private_project_after_set_to_private()
    {
        $project = $this->projects->first();
        $this->modifier->toSubmittedPrivateProject($project->project_id);
        $this->assetEqualToAttributes(
            Project::find($project->project_id),
            $this->profile->private_project
        );
    }

    /** @test */
    public function it_change_to_public_project_after_set_to_submitted()
    {
        $project = $this->projects->first();
        $this->modifier->toSubmittedPublicProject($project->project_id);
        $this->assetEqualToAttributes(
            Project::find($project->project_id),
            $this->profile->public_project
        );
    }

    /** @test */
    public function it_change_to_ongoing_after_approve_product()
    {
        $project = $this->projects->first();
        $this->modifier->approveProduct($project->project_id);
        $this->assetEqualToAttributes(
            Project::find($project->project_id),
            $this->profile->ongoing_product
        );
    }

    /** @test */
    public function it_change_to_draft_after_reject_product()
    {
        $project = $this->projects->first();
        $this->modifier->rejectProduct($project->project_id);
        $this->assetEqualToAttributes(
            Project::find($project->project_id),
            $this->profile->draft_product
        );
    }

    /** @test */
    public function it_set_postpone_time_when_postpone()
    {
        $project = $this->projects->first();
        $this->modifier->postponeProduct($project->project_id);

        $p = Project::find($project->project_id);

        $postpone_time = Carbon::parse($p->postpone_time)->toDateString();
        $now           = Carbon::now()->toDateString();

        $this->assertEquals($postpone_time, $now);
    }

    /** @test */
    public function it_reset_perk_date_after_recover_postpone()
    {
        $project = Factory::create(Eloquent\Project::class);

        $project->update([
            'postpone_time'        => Carbon::yesterday()->toDateString(),
            'manufactuer_end_date' => Carbon::now()->addDay()->toDateString(),
            'end_date'             => Carbon::now()->addDays(2)->toDateString()
        ]);

        $this->modifier->recoverPostponeProduct($project->project_id);

        /**
         * after recover
         * expert-only perk should have 2 days
         * customer perk should have 3 days
         */
        $modified = Project::find($project->project_id);

        $expert_days_left = Carbon::now()->diffInDays(
            Carbon::parse($modified->manufactuer_end_date),
            $abs = false
        );

        $customer_days_left = Carbon::now()->diffInDays(
            Carbon::parse($modified->end_date),
            $abs = false
        );

        $this->assertEquals(2, $expert_days_left);
        $this->assertEquals(3, $customer_days_left);
    }

    /** @test */
    public function it_copy_duplicate_columns_to_project_and_delete_duplicate_after_approve()
    {
        $project   = $this->projects->first();
        $duplicate = Factory::create(Eloquent\DuplicateProject::class);
        $duplicate->update([
            'project_id'    => $project->project_id,
            'project_title' => 'Title From Duplicate'
        ]);

        $this->modifier->approveDuplicateProduct($project->project_id);
        $this->assertNull(DuplicateProject::find($project->project_id));
        $this->assertEquals('Title From Duplicate', Project::find($project->project_id)->project_title);
    }

    /** @test */
    public function it_delete_duplicate_project_after_reject()
    {
        $duplicate = Factory::create(Eloquent\DuplicateProject::class);
        $this->modifier->rejectDuplicateProduct($duplicate->project_id);
        $this->assertNull(DuplicateProject::find($duplicate->project_id));
    }
}
