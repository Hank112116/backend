<?php

use Backend\Model\Eloquent;
use Backend\Model\Eloquent\ReferenceProject;
use Laracasts\TestDummy\Factory;

class LandingReferenceProjectRepoTest extends BackendTestCase
{
    private $repo;

    public function setUp()
    {
        parent::setUp();
        $this->repo = App::make('Backend\Repo\RepoInterfaces\LandingReferProjectInterface');
    }
    
    /** @test */
    public function it_fetches_all_reference_project_asc_by_order()
    {
        Factory::times(3)->create(Eloquent\ReferenceProject::class);

        $projects = $this->repo->all();
        $this->assertEquals(3, $projects->count());
        $this->assertTrue($projects->first()->order < $projects->last()->order);
    }

    /** @test */
    public function it_fetches_entity_by_project_id()
    {
        $project = Factory::create(Eloquent\Project::class);

        $this->assertFalse($this->repo->byProjectId(0));
        $this->assertEquals($project->project_id, $this->repo->byProjectId($project->project_id)->project_id);
    }

    /** @test */
    public function it_reset_reference_projects()
    {
        $projects = Factory::times(3)->create(Eloquent\ReferenceProject::class);

        foreach ($projects as $index => $project) {
            $project->url_project_title = "FOO_{$index}";
        }

        $this->repo->reset($projects->toArray());
        $fetches = $this->repo->all();

        ReferenceProject::truncate();

        $this->assertTrue(str_contains($fetches->first()->url_project_title, 'FOO'));
    }
}
