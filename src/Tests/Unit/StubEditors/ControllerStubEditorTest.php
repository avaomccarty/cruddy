<?php

namespace Cruddy\Tests\Unit\StubEditors;

use Cruddy\StubEditors\ControllerStub;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class ControllerStubTest extends TestCase
{
    /**
     * A test to get the controller API stub file.
     *
     * @return void
     */
    public function test_get_api_stub_file()
    {
        $isApi = true;
        $stubsLocation = 'stubs';
        $expectedResult = File::get(dirname(dirname(dirname(__DIR__))) . '/Commands/' . $stubsLocation . '/controller.api.stub');

        $mock = $this->partialMock(ControllerStub::class, function (MockInterface $mock) use ($isApi, $stubsLocation) {
            $mock->shouldAllowMockingProtectedMethods();
        });

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->once()
            ->andReturn('stubs');

        Config::partialMock();

        $mock->setIsApi($isApi);
        $result = $mock->getStubFile();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the default controller stub file.
     *
     * @return void
     */
    public function test_get_default_stub_file()
    {
        $isApi = false;
        $stubsLocation = 'stubs';
        $expectedResult = File::get(dirname(dirname(dirname(__DIR__))) . '/Commands/' . $stubsLocation . '/controller.stub');

        $mock = $this->partialMock(ControllerStub::class, function (MockInterface $mock) use ($isApi, $stubsLocation) {
            $mock->shouldAllowMockingProtectedMethods();
        });

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->once()
            ->andReturn('stubs');

        Config::partialMock();

        $mock->setIsApi($isApi);
        $result = $mock->getStubFile();

        $this->assertSame($expectedResult, $result);
    }
}