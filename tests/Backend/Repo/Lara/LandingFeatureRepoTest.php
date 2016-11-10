<?php

use Backend\Model\Eloquent;
use Laracasts\TestDummy\Factory;

class LandingFeatureRepoTest extends BackendTestCase
{
    private $repo;

    public function setUp()
    {
        parent::setUp();
        $this->repo = app()->make('Backend\Repo\RepoInterfaces\LandingFeatureInterface');
    }

    /** @test */
    public function it_fetches_entity_by_type()
    {
        $expert   = Factory::create(Eloquent\User::class);
        $expert_feature = $this->repo->byEntityIdType($expert->user_id, 'expert');
        $this->assertEquals($expert_feature->entity->user_id, $expert->user_id);

        $project  = Factory::create(Eloquent\Project::class);
        $project_feature = $this->repo->byEntityIdType($project->project_id, 'project');
        $this->assertEquals($project_feature->entity->project_id, $project->project_id);

        $solution = Factory::create(Eloquent\Solution::class);
        $solution_feature = $this->repo->byEntityIdType($solution->solution_id, 'solution');
        $this->assertEquals($solution_feature->entity->solution_id, $solution->solution_id);
    }
}
