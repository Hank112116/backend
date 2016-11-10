<?php

use Carbon\Carbon;
use Laracasts\TestDummy\Factory;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Model\Eloquent;

class UserRepoTest extends BackendTestCase
{
    private $repo;

    public function setUp()
    {
        parent::setUp();

        $this->repo = app()->make('Backend\Repo\RepoInterfaces\UserInterface');
    }

    /** @test */
    public function it_can_search_users_by_id()
    {
        $user = Factory::create(Eloquent\User::class);

        $collections = $this->repo->byId($user->user_id);
        $this->assertEquals(1, $collections->count());
    }

    /** @test */
    public function it_can_search_users_by_name()
    {
        Factory::times(10)->create(Eloquent\User::class, [
            'user_name' => 'NO_IMPOSSIBLE_NAME'
        ]);

        $collections = $this->repo->byName('NO_IMPOSSIBLE_NAME');
        $this->assertEquals(10, $collections->count());
    }

    /** @test */
    public function it_can_search_users_by_email()
    {
        Factory::create(Eloquent\User::class, [
            'email' => 'jaster1019@gmail.com'
        ]);

        $collections = $this->repo->byMail('jaster1019@gmail.com');
        $this->assertEquals(1, $collections->count());
    }

    /** @test */
    public function it_can_search_users_by_date_range()
    {
        Factory::times(1)->create(Eloquent\User::class, [
            'date_added' => Carbon::now()->addDays(- 5)->toDateTimeString()
        ]);
        Factory::times(2)->create(Eloquent\User::class, [
            'date_added' => Carbon::now()->addDays(- 4)->toDateTimeString()
        ]);
        Factory::times(3)->create(Eloquent\User::class, [
            'date_added' => Carbon::now()->addDays(- 3)->toDateTimeString()
        ]);
        Factory::times(4)->create(Eloquent\User::class, [
            'date_added' => Carbon::now()->addDays(- 2)->toDateTimeString()
        ]);

        $collections = $this->repo->byDateRange(
            Carbon::now()->addDays(- 3)->toDateString(),
            Carbon::now()->toDateString()
        );

        $this->assertEquals(7, $collections->count());
    }

    /** @test */
    public function it_validate_update_fail_with_bad_formatted_email()
    {
        $normal_formatted_mail = 'one-email@gmail.com';
        $bad_formatted_mail = '....bad-email';

        $user   = Factory::create(Eloquent\User::class, ['email' => $normal_formatted_mail]);

        $this->assertFalse($this->repo->validUpdate($user->user_id, [
            'email' => $bad_formatted_mail
        ]));

        $this->assertTrue($this->repo->errors()->messages()->has('email'));

    }

    /** @test */
    public function it_validate_update_fail_when_email_repeated()
    {
        $repeated_mail = 'repeat-one@gmail.com';

        $repeat = Factory::create(Eloquent\User::class, ['email' => $repeated_mail]);
        $user   = Factory::create(Eloquent\User::class, ['email' => 'good-email@gmail.com']);

        $this->assertTrue($this->repo->validUpdate($repeat->user_id, [
            'email' => $repeated_mail
        ]));

        $this->assertFalse($this->repo->validUpdate($user->user_id, [
            'email' => $repeated_mail
        ]));
    }

    /** @test */
    public function it_return_outputs()
    {
        $users = Factory::times(4)->create(Eloquent\User::class);
        $outputs = $this->repo->toOutputArray($users);

        $paged_users = $this->repo->byPage($page = 1, $limit = 2);
        $paged_outputs = $this->repo->toOutputArray($paged_users);

        $this->assertEquals(4, count($outputs));
        $this->assertEquals(2, count($paged_outputs));
    }
}
