<?php

namespace Cruddy\Tests\Unit\Traits\Stubs;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\Stubs\StubTrait;
use Illuminate\Support\Facades\File;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class StubTraitTest extends TestCase
{
    use StubTrait, TestTrait;

    /**
     * The formatting at the end of the line.
     *
     * @var string
     */
    protected $endOfLine = 'endOfLine';

    /**
     * A test getting the end of the line default value.
     *
     * @return void
     */
    public function test_get_end_of_line_default()
    {
        unset($this->endOfLine);

        $result = $this->getEndOfLine();

        $this->assertSame($this->defaultEndOfLine, $result);
    }

    /**
     * A test getting the end of the line value.
     *
     * @return void
     */
    public function test_get_end_of_line()
    {
        $result = $this->getEndOfLine();

        $this->assertSame($this->getEndOfLine(), $result);
    }

    /**
     * A test to remove unneeded formatting at the end of the string.
     *
     * @return void
     */
    public function test_remove_formatting_at_end_of_string()
    {
        $expectedResult = $value = 'value';
        $endOfLine = $this->getEndOfLine();
        $value = $value . $endOfLine;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($value, $endOfLine) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('hasEndOfLineFormatting')
                ->with($value)
                ->once()
                ->andReturn(true);
            $mock->shouldReceive('getEndOfLine')
                ->once()
                ->andReturn($endOfLine);
        });

        $mock->removeEndOfLineFormatting($value);

        $this->assertSame($expectedResult, $value, 'The string does not match the expected result.');
        $this->assertFalse(strpos($value, $this->getEndOfLine()), 'The formatting was found within the result.');
    }

    /**
     * A test for needs formatting removed when formatting needs to be removed.
     *
     * @return void
     */
    public function test_needs_formatting_removed_when_formatting_needs_removed()
    {
        $endOfLine = $this->getEndOfLine();
        $value = 'value' . $endOfLine;
        
        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($endOfLine) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getEndOfLine')
                ->twice()
                ->andReturn($endOfLine);
        });
        

        $result = $mock->hasEndOfLineFormatting($value);

        $this->assertTrue($result);
    }

    /**
     * A test for needs formatting removed when formatting does not need to be removed.
     *
     * @return void
     */
    public function test_needs_formatting_removed_when_formatting_does_not_need_removed()
    {
        $endOfLine = $this->getEndOfLine();
        $value = $endOfLine . 'value';
        
        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($endOfLine) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getEndOfLine')
                ->twice()
                ->andReturn($endOfLine);
        });
        

        $result = $mock->hasEndOfLineFormatting($value);

        $this->assertFalse($result);
    }

    /**
     * A test for needs formatting removed when empty string used.
     *
     * @return void
     */
    public function test_needs_formatting_removed_when_empty_string_used()
    {
        $endOfLine = $this->getEndOfLine();
        $value = '';
        
        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($endOfLine) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getEndOfLine')
                ->twice()
                ->andReturn($endOfLine);
        });
        

        $result = $mock->hasEndOfLineFormatting($value);

        $this->assertFalse($result);
    }

    /**
     * A test getting the class basename.
     *
     * @return void
     */
    public function test_get_class_basename()
    {
        $className = 'Class';
        $model = 'Foo\Bar\Baz\\' . $className;

        $result = $this->getClassBasename($model);

        $this->assertSame(strtolower($className), $result);
    }


    /**
     * A test to resolve the path to the stub when file exists.
     *
     * @return void
     */
    public function test_resolve_stub_path()
    {
        $stub = 'stub';
        $expectedResult = $path = base_path(trim($stub, '/'));

        File::shouldReceive('exists')
            ->with($path)
            ->once()
            ->andReturn(true);

        $result = $this->resolveStubPath($stub);

        $this->assertIsString($result, 'The type should be a string.');
        $this->assertNotEmpty($result, 'The type should not be empty.');
        $this->assertSame($expectedResult, $result, 'The stub is incorrect.');
    }

    /**
     * A test to resolve the path to the stub when file does not exists.
     *
     * @return void
     */
    public function test_resolve_stub_path_when_file_not_found()
    {
        $stub = 'stub';
        $expectedResult = dirname(dirname(dirname(dirname(__DIR__)))) . '/Commands/' . $stub;

        File::shouldReceive('exists')
            ->once()
            ->andReturn(false);

        $result = $this->resolveStubPath($stub);

        $this->assertIsString($result, 'The type should be a string.');
        $this->assertNotEmpty($result, 'The type should not be empty.');
        $this->assertSame($expectedResult, $result, 'The stub is incorrect.');
    }
}