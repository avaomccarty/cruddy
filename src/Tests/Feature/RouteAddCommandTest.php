<?php

namespace Cruddy\Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;

class RouteAddCommandTest extends TestCase
{
    /**
     * A test for the output when the file does not exist.
     *
     * @return void
     */
    public function test_file_does_not_exist()
    {
        Cache::shouldReceive('exists')
            ->with('routes/web.php')
            ->once()
            ->andReturn(false);

        Cache::makePartial();

        $this->artisan('cruddy:route', [
            'name' => 'web'
        ])
            ->expectsOutput("No Cruddy resource routes were added.\n");
    }
}
