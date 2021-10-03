<?php

namespace Cruddy\Tests\Feature;

use Cruddy\ServiceProvider;
use Cruddy\Traits\CommandTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;

class ModelMakeCommandTest extends TestCase
{
    use DatabaseTransactions, CommandTrait;

    protected $loadEnvironmentVariables = true;

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    public function setUp() : void
    {
        parent::setUp();
    }

    /**
     * Get a usable mock of the model stub.
     *
     * @return string
     */
    protected function modelStubMock() : string
    {
        return '';
    }

    // /**
    //  * A test 
    //  *
    //  * @test
    //  * @group cruddy
    //  * @return void
    //  */
    // public function test_one()
    // {
    //     $folder = 'cruddy/test_stubs';
    //     $stub = $folder . '/model.stub';
    //     Config::shouldReceive('get')
    //         ->with('cruddy.stubs_folder')
    //         ->once()
    //         ->andReturn($folder);

    //     Config::partialMock();

    //     File::shouldReceive('exists')
    //         ->andReturn(true);

    //     File::shouldReceive('get')
    //         ->andReturn($this->modelStubMock());

    //     File::partialMock();

    //     $this->artisan('cruddy:model', [
    //         'name' => 'test',
    //         '--inputs' => ['foo']
    //     ])->assertExitCode(0);
    // }
    
}