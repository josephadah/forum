<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();

        DB::statement('PRAGMA foreign_keys=on;');
    }

    protected function signIn($user = null)
    {
        $user = $user ?: factory('App\User')->create();

        $this->actingAs($user);

        return $this;
    }
    

}
