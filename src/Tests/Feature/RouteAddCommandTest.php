<?php

namespace Cruddy\Tests\Feature;

use Cruddy\Tests\TestTrait;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;

class RouteAddCommandTest extends TestCase
{
    use TestTrait;

    /**
     * The success message.
     *
     * @var string
     */
    protected $successMessage = "Cruddy resource routes were added successfully!\n";

    /**
     * The no routes were added message.
     *
     * @var string
     */
    protected $noRoutesAddedMessage = "No Cruddy resource routes were added.\n";

    /**
     * A test for adding the new import statement to the default route file.
     *
     * @test
     * @group cruddy
     * @return void
     */
    public function test_import_statement_added_to_default_route_file()
    {
        $name = 'name';
        $file = 'routes/web.php';
        $resourceRoute = "\n\n// Name Resource\n" . 
        "Route::resource('names', 'App\Http\Controllers\NameController');";

        File::shouldReceive('exists')
            ->with($file)
            ->once()
            ->andReturn(true);

        File::shouldReceive('get')
            ->with($file)
            ->once()
            ->andReturn('');

        File::shouldReceive('append')
            ->with($file, $resourceRoute)
            ->once();

        File::partialMock();

        $this->artisan('cruddy:route', [
            'name' => $name
        ])->expectsOutput($this->successMessage);
    }
    
    /**
     * A test for adding the new import statement to the api route file.
     *
     * @test
     * @group cruddy
     * @return void
     */
    public function test_import_statement_added_to_api_route_files()
    {
        $name = 'name';
        $file = 'routes/api.php';
        $isApi = true;
        $resourceRoute = "\n\n// Name Resource\n" . 
        "Route::apiResource('names', 'App\Http\Controllers\NameController');";

        File::shouldReceive('exists')
            ->with($file)
            ->once()
            ->andReturn(true);

        File::shouldReceive('get')
            ->with($file)
            ->once()
            ->andReturn('');

        File::shouldReceive('append')
            ->with($file, $resourceRoute)
            ->once();

        File::partialMock();

        $this->artisan('cruddy:route', [
            'name' => $name,
            '--api' => $isApi
        ])->expectsOutput($this->successMessage);
    }
    
    /**
     * A test for the route statement not being duplicated within the route file.
     *
     * @test
     * @group cruddy
     * @return void
     */
    public function test_import_statement_not_duplicated_in_route_file()
    {
        $name = 'name';
        $file = 'routes/web.php';
        $resourceRoute = "\n\n// Name Resource\n" . 
        "Route::resource('names', 'App\Http\Controllers\NameController');";

        File::shouldReceive('exists')
            ->with($file)
            ->once()
            ->andReturn(true);

        File::shouldReceive('get')
            ->with($file)
            ->once()
            ->andReturn($resourceRoute);

        File::shouldReceive('append')
            ->with($file, $resourceRoute)
            ->never();

        $this->artisan('cruddy:route', [
            'name' => $name,
        ])->expectsOutput($this->noRoutesAddedMessage);
    }
    
    /**
     * A test for when the route file does not exist.
     *
     * @test
     * @group cruddy
     * @return void
     */
    public function test_route_file_does_not_exist()
    {
        $name = 'name';
        $file = 'routes/web.php';
        $resourceRoute = "\n\n// Name Resource\n" . 
        "Route::resource('names', 'App\Http\Controllers\NameController');";

        File::shouldReceive('exists')
            ->with($file)
            ->once()
            ->andReturn(false);

        File::shouldReceive('get')
            ->with($file)
            ->never()
            ->andReturn($resourceRoute);

        File::shouldReceive('append')
            ->with($file, $resourceRoute)
            ->never();

        $this->artisan('cruddy:route', [
            'name' => $name,
        ])->expectsOutput($this->noRoutesAddedMessage);
    }
}