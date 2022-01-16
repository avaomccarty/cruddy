<?php

namespace Cruddy\Tests\Unit\StubEditors;

use Cruddy\StubEditors\StubEditor;
use Cruddy\StubEditors\StubEditorFactory;
use Orchestra\Testbench\TestCase;

class StubEditorTest extends TestCase
{
    /**
     * A test to get the stub.
     *
     * @return void
     */
    public function test_get_stub()
    {
        $stub = $expectedResult = 'stub';
        $stubEditor = (new StubEditorFactory('controller', $stub))
            ->get();

        $result = $stubEditor->getStub();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to replace variables in a stub.
     *
     * @return void
     */
    public function test_replace_in_stub()
    {
        $stub = 'foo-bar-baz-';
        $stubEditor = (new StubEditorFactory('controller', $stub))
            ->get();

        $replaceValue = 'replaceValue-';
        $variables = [
            'foo-',
            'bar-',
            'baz-',
        ];
        $expectedResultStub = '';

        for ($x = 0; $x < count($variables); $x++) {
            $expectedResultStub .= $replaceValue;
        }

        $result = $stubEditor->replaceInStub($variables, $replaceValue);
        $resultStub = $stubEditor->getStub();

        $this->assertInstanceOf(StubEditor::class, $result);
        $this->assertSame($expectedResultStub, $resultStub);
    }
}
