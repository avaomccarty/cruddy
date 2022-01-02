<?php

namespace Cruddy\Tests\Unit\StubEditors;

use Cruddy\StubEditors\ControllerStubEditor;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class ControllerStubEditorTest extends TestCase
{
    /**
     * A test to get the controller API stub file.
     *
     * @return void
     */
    public function test_get_api_stub_file()
    {
        $isApi = true;
        $stubsLocation = 'stubs/cruddy';
        $expectedResult = dirname(dirname(dirname(__DIR__))) . '/Commands/' . $stubsLocation . '/controller.api.stub';

        $mock = $this->partialMock(ControllerStubEditor::class, function (MockInterface $mock) use ($isApi, $stubsLocation) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getApi')
                ->once()
                ->andReturn($isApi);
            $mock->shouldReceive('getStubsLocation')
                ->once()
                ->andReturn($stubsLocation);
        });

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
        $stubsLocation = 'stubs/cruddy';
        $expectedResult = dirname(dirname(dirname(__DIR__))) . '/Commands/' . $stubsLocation . '/controller.stub';

        $mock = $this->partialMock(ControllerStubEditor::class, function (MockInterface $mock) use ($isApi, $stubsLocation) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getApi')
                ->once()
                ->andReturn($isApi);
            $mock->shouldReceive('getStubsLocation')
                ->once()
                ->andReturn($stubsLocation);
        });

        $result = $mock->getStubFile();

        $this->assertSame($expectedResult, $result);
    }
}