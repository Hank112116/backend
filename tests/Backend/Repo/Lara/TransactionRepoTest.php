<?php

use Carbon\Carbon;
use Laracasts\TestDummy\Factory;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Model\Eloquent;

class TransactionRepoTest extends BackendTestCase
{
    private $repo;

    public function setUp()
    {
        parent::setUp();

        $this->repo = App::make('Backend\Repo\RepoInterfaces\TransactionInterface');
    }

    /** @test */
    public function it_fetches_transactions_by_page()
    {
        Factory::times(5)->create(Eloquent\Transaction::class);

        $transactions = $this->repo->byPage($page = 1, $limit = 2);

        $this->assertEquals(2, $transactions->count());
    }

    /** @test */
    public function it_fetches_transactions_by_both_user_name_and_last_name()
    {
        $user1 = Factory::create(Eloquent\User::class, [
            'user_name' => 'USER_NAME',
        ]);

        $user2 = Factory::create(Eloquent\User::class, [
            'last_name' => 'LAST_NAME'
        ]);

        Factory::create(Eloquent\Transaction::class, [
            'user_id' => $user1->user_id
        ]);

        Factory::create(Eloquent\Transaction::class, [
            'user_id' => $user2->user_id
        ]);

        $transactions = $this->repo->byUserName('USER_NAME LAST_NAME');
        $this->assertEquals(2, $transactions->count());
    }

    /** @test */
    public function it_fetches_transactions_by_project_id()
    {
        Factory::times(3)->create(Eloquent\Transaction::class, [
            'project_id' => 1
        ]);

        $transactions = $this->repo->byProjectId(1);
        $this->assertEquals(3, $transactions->count());
    }

    /** @test */
    public function it_fetches_transactions_by_project_title()
    {
        $project = Factory::create(Eloquent\Project::class, [
            'project_title' => 'PROJECT_TITLE'
        ]);

        Factory::times(3)->create(Eloquent\Transaction::class, [
            'project_id' => $project->project_id
        ]);

        $transactions = $this->repo->byProjectTitle('PROJECT_TITLE');

        $this->assertEquals(3, $transactions->count());
    }

    /** @test */
    public function it_fetches_transactions_by_date()
    {
        Factory::times(1)->create(Eloquent\Transaction::class, [
            'transaction_date_time' => Carbon::now()->addDays(-2)->toDateTimeString()
        ]);

        Factory::times(2)->create(Eloquent\Transaction::class, [
            'transaction_date_time' => Carbon::now()->addDays(-4)->toDateTimeString()
        ]);

        Factory::times(3)->create(Eloquent\Transaction::class, [
            'transaction_date_time' => Carbon::now()->addDays(-6)->toDateTimeString()
        ]);


        $transactions = $this->repo->byDateRange(
            Carbon::now()->addDays(-3)->toDateTimeString(),
            Carbon::now()->addDays(-1)->toDateTimeString()
        );
        $this->assertEquals(1, $transactions->count());


        $transactions = $this->repo->byDateRange(
            Carbon::now()->addDays(-5)->toDateTimeString(),
            Carbon::now()->addDays(-3)->toDateTimeString()
        );
        $this->assertEquals(2, $transactions->count());


        $transactions = $this->repo->byDateRange(
            Carbon::now()->addDays(-7)->toDateTimeString(),
            Carbon::now()->addDays(-5)->toDateTimeString()
        );
        $this->assertEquals(3, $transactions->count());
    }
}
