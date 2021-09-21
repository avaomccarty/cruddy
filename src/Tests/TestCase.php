<?php

namespace Cruddy\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
// use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
// use Cruddy\Tests\CreatesApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    // use CreatesApplication;

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            // Note: Add service provider classes here (e.g. Foo::class).
        ];
    }

        /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Note: Add environment setup here.
    }


}
