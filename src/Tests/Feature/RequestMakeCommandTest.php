<?php

namespace Cruddy\Tests\Feature;

use Cruddy\ServiceProvider;
use Cruddy\Tests\TestTrait;
use Cruddy\Traits\RequestMakeCommandTrait;
use Cruddy\Traits\Stubs\RuleTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;

class RequestMakeCommandTest extends TestCase
{
    use RequestMakeCommandTrait, RuleTrait, TestTrait;

    /**
     * The output from successfully running the command.
     *
     * @var string
     */
    protected $successOutput = 'Cruddy request created successfully.';

    /**
     * The location of the stubs folder.
     *
     * @var string
     */
    protected $stubPath = '/stubs';

    public function setUp() : void
    {
        parent::setUp();
        $this->name = 'test';
    }

    /**
     * Get the assertions based on the type of request file being created.
     *
     * @param  string  $type
     * @return 
     */
    public function getAssertionsByType(string $type)
    {
        $fileType = 'request';
        $requestFileName = ucfirst($type) . ucfirst($this->name);
        $stubPath = 'stubs';
        $stubLocation = $this->getStubLocation($fileType);
        $stub = File::get($stubLocation);

        $expectedRequestFileLocation = $this->getExpectedRequestFileLocation($requestFileName);
        $expectedRequestFile = $this->getExpectedRequestFile($type);

        File::shouldReceive('exists')
            ->with($expectedRequestFileLocation)
            ->once()
            ->andReturn(false);

        File::shouldReceive('get')
            ->with($stubLocation)
            ->once()
            ->andReturn($stub);

        File::shouldReceive('put')
            ->with($expectedRequestFileLocation, $expectedRequestFile)
            ->once();

        File::partialMock();

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->once()
            ->andReturn($stubPath);

        Config::partialMock();

        $this->artisan('cruddy:request', [
            'name' => $this->name,
            'type' => $type,
            'rules' => $this->getMockColumns()
        ])
            ->expectsOutput($this->successOutput)
            ->assertExitCode(0);

        
        // Assert that the expected request file does not have any stub rule placeholders remaining
        foreach ($this->stubRulePlaceholders as $placeholder) {
            $this->assertFalse(strpos($expectedRequestFile, $placeholder));
        }
    }

    /**
     * A test for correct update request file.
     *
     * @test
     * @group cruddy
     * @return void
     */
    public function test_correct_update_request_file_created()
    {
        $this->getAssertionsByType('update');
    }

    /**
     * A test for correct store request file.
     *
     * @test
     * @group cruddy
     * @return void
     */
    public function test_correct_store_request_file_created()
    {
        $this->getAssertionsByType('store');
    }

}