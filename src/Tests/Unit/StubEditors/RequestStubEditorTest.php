<?php

namespace Cruddy\Tests\Unit\StubEditors;

use Cruddy\StubEditors\RequestStub;
use Illuminate\Support\Facades\File;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class RequestStubTest extends TestCase
{
    // Todo: add unit tests to handle the $endOfLine for each stub editor that hs the property


    /**
     * A test to get the default request stub file.
     *
     * @return void
     */
    public function test_get_default_stub_file()
    {
        $stubsLocation = 'stubs';
        $expectedResult = File::get(dirname(dirname(dirname(__DIR__))) . '/Commands/' . $stubsLocation . '/request.stub');

        $mock = $this->partialMock(RequestStub::class, function (MockInterface $mock) use ($stubsLocation) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getStubsLocation')
                ->once()
                ->andReturn($stubsLocation);
        });

        $result = $mock->getStubFile();

        $this->assertSame($expectedResult, $result);
    }
}