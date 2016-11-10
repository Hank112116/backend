<?php

use Backend\Model\Eloquent;
use Backend\Model\Eloquent\Solution;
use Laracasts\TestDummy\Factory;

class SolutionRepoTest extends BackendTestCase
{
    private $repo;
    private $solution;

    public function setUp()
    {
        parent::setUp();
        $this->repo = app()->make('Backend\Repo\RepoInterfaces\SolutionInterface');
        $this->solution = new Solution;
    }

    /** @test */
    public function it_fetches_solutions_that_is_submitted()
    {
        $after_submit_status = $this->solution->after_submitted_status;

        $solutions = Factory::times(5)->create(Eloquent\Solution::class);
        $solutions->each(function ($s) use ($after_submit_status) {
            $s->update($after_submit_status);
        });

        $solutions->first()->update($this->solution->draft_status);
        $solutions->last()->update($this->solution->draft_status);

        $fetches = $this->repo->approvedSolutions($page = 1, $limit = 2);

        $this->assertEquals(2, $fetches->count());
    }

    /** @test */
    public function it_fetches_solutions_that_is_draft()
    {
        $draft_status = $this->solution->draft_status;

        $solutions = Factory::times(2)->create(Eloquent\Solution::class);
        $solutions->each(function ($s) use ($draft_status) {
            $s->update($draft_status);
        });

        $fetches = $this->repo->drafts();
        $this->assertEquals(2, $fetches->count());
    }

    /** @test */
    public function it_fetches_solutions_that_is_waiting_approve()
    {
        $wait_approve_status = $this->solution->wait_approve_status;

        $solutions = Factory::times(2)->create(Eloquent\Solution::class);
        $solutions->each(function ($s) use ($wait_approve_status) {
            $s->update($wait_approve_status);
        });

        $fetches = $this->repo->waitApproveSolutions();
        $this->assertEquals(2, $fetches->count());
    }

    /** @test */
    public function it_fetches_solutions_that_is_deleted()
    {
        $solutions = Factory::times(2)->create(Eloquent\Solution::class);
        $solutions->each(function ($s) {
            $s->update(['is_deleted' => 1]);
        });

        $fetches = $this->repo->deletedSolutions();
        $this->assertEquals(2, $fetches->count());
    }

    /** @test */
    public function it_fetches_solutions_by_both_user_name_and_last_name()
    {
        $user1 = Factory::create(Eloquent\User::class, [
            'user_name' => 'USER_NAME',
        ]);

        $user2 = Factory::create(Eloquent\User::class, [
            'last_name' => 'LAST_NAME'
        ]);

        Factory::create(Eloquent\Solution::class, [
            'user_id' => $user1->user_id
        ]);

        Factory::create(Eloquent\Solution::class, [
            'user_id' => $user2->user_id
        ]);

        $solutions = $this->repo->byUserName('USER_NAME LAST_NAME');
        $this->assertEquals(2, $solutions->count());
    }

    /** @test */
    public function it_fetches_solutions_by_solution_title()
    {
        Factory::create(Eloquent\Solution::class, [
            'solution_title' => 'SOLUTION_TITLE'
        ]);

        Factory::create(Eloquent\Solution::class, [
            'solution_title' => 'SOLUTION'
        ]);

        $solutions = $this->repo->byTitle('SOLUTION');
        $this->assertEquals(2, $solutions->count());
    }

    public function it_filters_manager_approved_solutions()
    {
        $solutions = Factory::times(3)->create(Eloquent\Solution::class);
        $solutions->each(function ($s) {
            $s->update(['is_manager_approved' => 1]);
        });
        $solutions->first()->update(['is_manager_approved' => 0]);

        $solutions = $this->repo->filterWaitManagerApproveSolutions($this->repo->all());
        $this->assertEquals(1, $solutions->count());
    }
}
