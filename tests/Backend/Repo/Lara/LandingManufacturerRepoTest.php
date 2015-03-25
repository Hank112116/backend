<?php

use Backend\Model\Eloquent\Manufacturer;
use Laracasts\TestDummy\Factory;

class LandingManufacturerRepoTest extends BackendTestCase
{
    private $repo;
    private $manufacturer;

    public function setUp()
    {
        parent::setUp();
        $this->repo = App::make('Backend\Repo\RepoInterfaces\LandingManufacturerInterface');
        $this->manufacturer = new Manufacturer;
    }

    /** @test */
    public function it_fetches_a_dummy()
    {
        $dummy = $this->repo->dummy();
        $this->assertEquals($this->manufacturer->getDefaultImage(), $dummy->img_url);
    }
}
