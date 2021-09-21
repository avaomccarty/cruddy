<?php

namespace Cruddy\Tests\Unit;

use Cruddy\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class RouteAddCommandTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A test for new route files.
     *
     * @return void
     */
    public function testNewFilesCreated()
    {
        $this->artisan('cruddy:route', [
            'name' => 'test'
        ])->expectsOutput('Something');
    }
}