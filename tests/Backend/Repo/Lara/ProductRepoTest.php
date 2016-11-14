<?php

use Carbon\Carbon;
use Laracasts\TestDummy\Factory;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Model\Eloquent;

class ProductRepoTest extends BackendTestCase
{
    private $repo;
    private $profile;

    public function setUp()
    {
        parent::setUp();
        $this->repo = app()->make('Backend\Repo\RepoInterfaces\ProductInterface');
        $this->profile = app()->make('Backend\Model\Plain\ProjectProfile');
    }


    /** @test */
    public function it_fetches_products_by_page()
    {
        Factory::times(5)->create('Product');

        $projects = $this->repo->byPage($page = 1, $limit = 2);

        $this->assertEquals(2, $projects->count());
    }

    /** @test */
    public function it_fetches_products_by_user_id()
    {
        $user = Factory::create(Eloquent\User::class);
        Factory::times(3)->create('Product', [
           'user_id' => $user->user_id
        ]);

        $projects = $this->repo->byUserId($user->user_id);

        $this->assertEquals(3, $projects->count());
    }

    /** @test */
    public function it_fetches_products_by_both_user_name_and_last_name()
    {
        $user1 = Factory::create(Eloquent\User::class, [
            'user_name' => 'USER_NAME',
        ]);

        $user2 = Factory::create(Eloquent\User::class, [
            'last_name' => 'LAST_NAME'
        ]);

        Factory::create('Product', [
            'user_id' => $user1->user_id
        ]);

        Factory::create('Product', [
            'user_id' => $user2->user_id
        ]);

        $transactions = $this->repo->byUserName('USER_NAME LAST_NAME');
        $this->assertEquals(2, $transactions->count());
    }

    /** @test */
    public function it_fetches_products_by_project_id()
    {
        $project = Factory::create('Product');
        $fetched = $this->repo->byProjectId($project->project_id);
        $this->assertEquals($project->project_id, $fetched->first()->project_id);
    }

    /** @test */
    public function it_fetches_products_by_project_title()
    {
        Factory::create('Product', [
            'project_title' => 'PROJECT_TITLE'
        ]);

        Factory::create('Product', [
            'project_title' => 'PROJECT'
        ]);

        $projects = $this->repo->byTitle('PROJECT');

        $this->assertEquals(2, $projects->count());
    }

    /** @test */
    public function it_fetched_products_by_update_time_range()
    {
        Factory::times(1)->create('Product', [
            'update_time' => Carbon::now()->addDays(-2)->toDateTimeString()
        ]);

        Factory::times(2)->create('Product', [
            'update_time' => Carbon::now()->addDays(-4)->toDateTimeString()
        ]);

        Factory::times(3)->create('Product', [
            'update_time' => Carbon::now()->addDays(-6)->toDateTimeString()
        ]);

        $projects = $this->repo->byDateRange(
            Carbon::now()->addDays(-3)->toDateTimeString(),
            Carbon::now()->addDays(-1)->toDateTimeString()
        );
        $this->assertEquals(1, $projects->count());


        $projects = $this->repo->byDateRange(
            Carbon::now()->addDays(-5)->toDateTimeString(),
            Carbon::now()->addDays(-3)->toDateTimeString()
        );
        $this->assertEquals(2, $projects->count());


        $projects = $this->repo->byDateRange(
            Carbon::now()->addDays(-7)->toDateTimeString(),
            Carbon::now()->addDays(-5)->toDateTimeString()
        );
        $this->assertEquals(3, $projects->count());
    }

    /** @test */
    public function it_fetched_products_that_is_waiting_for_approve()
    {
        $approve_status = $this->profile->wait_approve_product;

        $products = Factory::times(3)->create('Product');
        $products->each(function ($p) use ($approve_status) {
            $p->update($approve_status);
        });

        $fetches = $this->repo->waitApproves();
        $this->assertEquals(3, $fetches->count());
    }
}
