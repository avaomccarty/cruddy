<?php

namespace Cruddy\Tests\Unit\StubEditors;

use Cruddy\StubEditors\ModelStubEditor;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class ModelStubEditorTest extends TestCase
{
    /**
     * A test to get the default model stub file.
     *
     * @return void
     */
    public function test_get_default_stub_file()
    {
        $stubsLocation = 'stubs/cruddy';
        $expectedResult = dirname(dirname(dirname(__DIR__))) . '/Commands/' . $stubsLocation . '/model.stub';

        $mock = $this->partialMock(ModelStubEditor::class, function (MockInterface $mock) use ($stubsLocation) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getStubsLocation')
                ->once()
                ->andReturn($stubsLocation);
        });

        $result = $mock->getStubFile();

        $this->assertSame($expectedResult, $result);
    }
}