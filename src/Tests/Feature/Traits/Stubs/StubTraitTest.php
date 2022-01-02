<?php

namespace Cruddy\Tests\Feature\Traits\Stubs;

use Cruddy\Tests\TestTrait;
// use Cruddy\Traits\Stubs\StubTrait;
use Orchestra\Testbench\TestCase;

class StubTraitTest extends TestCase
{
//     use StubTrait, TestTrait;

//     /**
//      * A test to remove the end of line formatting from a string.
//      *
//      * @return void
//      */
//     public function test_removing_end_of_line_formatting()
//     {
//         $endOfLine = $this->getEndOfLine();
//         $expectedResult = $value = 'value-';
//         $result = $value . $endOfLine;

//         $this->removeEndOfLineFormatting($result);

//         $this->assertSame($expectedResult, $result);
//         $this->assertFalse(strpos($result, $endOfLine), 'The formatting was found within the result.');
//     }

//     /**
//      * A test to remove the end of line formatting from a string when it is not needed.
//      *
//      * @return void
//      */
//     public function test_removing_end_of_line_formatting_when_not_needed()
//     {
//         $endOfLine = $this->getEndOfLine();
//         $value = 'value-';
//         $expectedResult = $result = $endOfLine . $value;

//         $this->removeEndOfLineFormatting($result);

//         $this->assertSame($expectedResult, $result);
//         $this->assertTrue(substr_count($result, $endOfLine) === 1, 'The formatting should only be found once within the result.');
//         $this->assertSame(0, strpos($result, $endOfLine), 'The formatting should still be found at the beginning within the result.');
//     }
}