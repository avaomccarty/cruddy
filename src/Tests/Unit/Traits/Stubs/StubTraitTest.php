<?php

namespace Cruddy\Tests\Unit\Traits\Stubs;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\Stubs\StubTrait;
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
            $mock->shouldReceive('needsFormattingRemoved')
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
        

        $result = $mock->needsFormattingRemoved($value);

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
        

        $result = $mock->needsFormattingRemoved($value);

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
        

        $result = $mock->needsFormattingRemoved($value);

        $this->assertFalse($result);
    }
}