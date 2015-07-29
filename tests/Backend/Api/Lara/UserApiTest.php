<?php

use Backend\Model\Eloquent;
use Laracasts\TestDummy\Factory;

class UserApiTest extends BackendTestCase
{
    private $api;
    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = Factory::create(Eloquent\User::class);
        $this->api = App::make('Backend\Api\ApiInterfaces\UserApiInterface');
    }

    /** @test */
    public function it_return_user_basic_columns()
    {
        $response = $this->api->basicColumns($this->user->user_id);
        $json = $response->getContent();
        $this->assertEquals(
            $this->user->textFullName(),
            json_decode($json, true)['full_name']
        );
    }

    /** @test */
    public function it_return_nothing_if_user_not_exist()
    {
        $response = $this->api->basicColumns('no-such-id');
        $json = $response->getContent();
        $this->assertEquals(
            "",
            json_decode($json, true)['msg']
        );
    }
}
