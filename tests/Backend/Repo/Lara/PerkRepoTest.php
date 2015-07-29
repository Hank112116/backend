<?php

use Backend\Model\Eloquent;
use Backend\Model\Eloquent\Perk;
use Carbon\Carbon;
use Laracasts\TestDummy\Factory;
use Backend\Repo\RepoInterfaces\UserInterface;

class PerkRepoTest extends BackendTestCase
{
    private $repo;

    public function setUp()
    {
        parent::setUp();
        $this->repo = App::make('Backend\Repo\RepoInterfaces\PerkInterface');
    }


    /** @test */
    public function it_fetched_perks_by_project_id()
    {
        $product = Factory::create('Product');

        Factory::create(Eloquent\Perk::class, [
            'project_id' => 0
        ]);

        Factory::times(2)->create(Eloquent\Perk::class, [
            'project_id' => $product->project_id
        ]);

        $this->assertEquals(2, $this->repo->byProjectId($product->project_id)->count());
    }

    /** @test */
    public function it_fetched_perk_ids_that_is_editable()
    {
        Factory::create(Eloquent\Perk::class, [
            'perk_get' => 100
        ]);

        Factory::times(2)->create(Eloquent\Perk::class, [
            'perk_get' => 0
        ]);

        $this->assertEquals(2, count($this->repo->editablePerkIds()));
    }

    /** @test */
    public function it_create_new_perk()
    {
        $product     = Factory::create('Product');
        $update_data = [
            0 => [
                'is_new'     => true,
                'perk_title' => 'NEW_PERK'
            ]
        ];

        $this->repo->updateProjectPerks($product->project_id, $update_data);
        $this->assertEquals('NEW_PERK', Perk::first()->perk_title);
    }

    /** @test */
    public function it_update_perk()
    {
        $product = Factory::create('Product');
        $perk    = Factory::create(Eloquent\Perk::class, [
            'project_id' => $product->project_id
        ]);

        $update_data = [
            $perk->perk_id => [
                'perk_id'    => $perk->perk_id,
                'perk_title' => 'UPDATED_PERK'
            ]
        ];

        $this->repo->updateProjectPerks($product->project_id, $update_data);
        $this->assertEquals('UPDATED_PERK', Perk::first()->perk_title);
    }

    /** @test */
    public function it_generate_a_perk_entity()
    {
        $perk = $this->repo->newEntity();
        $this->assertEquals(1, $perk->is_new);
        $this->assertEquals(1, $perk->is_editable);
    }
}
