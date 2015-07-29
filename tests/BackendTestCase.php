<?php

use Laracasts\TestDummy\Factory;

class BackendTestCase extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();
        Factory::$factoriesPath = 'tests/factories';
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
    }
}
