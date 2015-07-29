<?php

use Backend\Model\Eloquent;
use Backend\Model\Eloquent\DuplicatePerk;
use Laracasts\TestDummy\Factory;

class DuplicatePerkRepoTest extends BackendTestCase
{
    private $perk_repo;
    private $duplicate_repo;

    public function setUp()
    {
        parent::setUp();

        $this->perk_repo = App::make('Backend\Repo\RepoInterfaces\PerkInterface');
        $this->duplicate_repo = App::make('Backend\Repo\RepoInterfaces\DuplicatePerkInterface');
    }

    /** @test */
    public function it_copy_duplicates_to_perks()
    {
        $project_id = 1;

        $perks = Factory::times(2)->create(Eloquent\Perk::class, [
            'project_id' => $project_id,
            'perk_title' => 'PERK_TITLE'
        ]);

        foreach ($perks as $perk) {
            Factory::create(Eloquent\DuplicatePerk::class, [
                'perk_id' => $perk->perk_id,
                'project_id' => $project_id,
                'perk_title' => 'DUPLICATE_PERK_TITLE'
            ]);
        }

        $this->duplicate_repo->coverPerks($project_id);

        $perk = $this->perk_repo->byProjectId($project_id)->first();
        $this->assertEquals('DUPLICATE_PERK_TITLE', $perk->perk_title);
    }

    /** @test */
    public function it_not_copy_duplicate_if_perk_is_not_editabe()
    {
        $project_id = 1;

        $perk = Factory::create(Eloquent\Perk::class, [
            'project_id' => $project_id,
            'perk_title' => 'PERK_TITLE',
            'perk_get'   => 100
        ]);

        Factory::create(Eloquent\DuplicatePerk::class, [
            'perk_id' => $perk->perk_id,
            'project_id' => $project_id,
            'perk_title' => 'DUPLICATE_PERK_TITLE'
        ]);

        $this->duplicate_repo->coverPerks($project_id);

        $perk = $this->perk_repo->byProjectId($project_id)->first();
        $this->assertEquals('PERK_TITLE', $perk->perk_title);
    }

    /** @test */
    public function it_create_perk_if_duplicate_is_new()
    {
        $project_id = 1;

        Factory::create(Eloquent\DuplicatePerk::class, [
            'project_id' => $project_id,
            'perk_title' => 'DUPLICATE_PERK_TITLE'
        ]);

        DuplicatePerk::where('project_id', $project_id)->update(['is_new' => 1]);

        $this->duplicate_repo->coverPerks($project_id);

        $perk = $this->perk_repo->byProjectId($project_id)->first();
        $this->assertEquals('DUPLICATE_PERK_TITLE', $perk->perk_title);
    }
}
