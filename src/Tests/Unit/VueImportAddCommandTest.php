<?php

namespace Cruddy\Tests\Unit;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\CommandTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class VueImportAddCommandTest extends TestCase
{
    use CommandTrait, TestTrait;

    // /**
    //  * A test to get the default namespace.
    //  *
    //  * @return void
    //  */
    // public function test_get_default_namespace()
    // {
    //     $types = $this->getDefaultTypes();
    //     $styles = [
    //         null, // This represents the default argument for the function.
    //         'kebab',
    //     ];
    //     $name = 'nameThatShouldBeUpdated';

    //     foreach ($types as $type) {        
    //         $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($name, $type, $styles) {
    //             $mock->shouldAllowMockingProtectedMethods();
    //             $mock->shouldReceive('getType')
    //                 ->times(count($styles))
    //                 ->andReturn($type);
    //             $mock->shouldReceive('argument')
    //                 ->with('name')
    //                 ->times(count($styles))
    //                 ->andReturn($name);
    //         });

    //         foreach ($styles as $style) {
    //             $result = $mock->getComponent($style);
    //             $expectedVariableName = 'expectedNameResult' . ucfirst($style);

    //             $this->assertIsString($result, 'The name should be a string.');
    //             $this->assertNotEmpty($result, 'The name shouldn\'t be empty.');
    //             $this->assertSame($$expectedVariableName, $result, 'The name is incorrect.');
    //         }
    //     }
    // }

    // /**
    //  * A test to get the component statement.
    //  *
    //  * @return void
    //  */
    // public function test_get_component_statement()
    // {
    //     $types = $this->getDefaultTypes();
    //     $styles = [
    //         null, // This represents the default argument for the function.
    //         'kebab',
    //     ];
    //     $name = 'nameThatShouldBeUpdated';

    //     foreach ($types as $type) {
    //         $expectedNameResultKebab = 'name-that-should-be-updated-' . strtolower($type);
    //         $expectedNameResult = 'NameThatShouldBeUpdated' . ucfirst($type);
    //         $expectedResult = "Vue.component('" . $expectedNameResultKebab . "', " . $expectedNameResult . ");\n";
        
    //         $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($name, $type, $styles) {
    //             $mock->shouldAllowMockingProtectedMethods();
    //             $mock->shouldReceive('getType')
    //                 ->times(count($styles))
    //                 ->andReturn($type);
    //             $mock->shouldReceive('argument')
    //                 ->with('name')
    //                 ->times(count($styles))
    //                 ->andReturn($name);
    //         });

    //         $result = $mock->getComponentStatement();

    //         $this->assertIsString($result, 'The name should be a string.');
    //         $this->assertNotEmpty($result, 'The name shouldn\'t be empty.');
    //         $this->assertSame($expectedResult, $result, 'The name is incorrect.');
    //     }
    // }

    // /**
    //  * A test to get the import statement.
    //  *
    //  * @return void
    //  */
    // public function test_get_import_statement()
    // {
    //     $types = $this->getDefaultTypes();
    //     $name = 'testNames';

    //     foreach ($types as $type) {
    //         $expectedResult = "import TestNames" . ucfirst($type) . " from '@/components/testNames/" . $type . ".vue';\n";
        
    //         $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($name, $type) {
    //             $mock->shouldAllowMockingProtectedMethods();
    //             $mock->shouldReceive('argument')
    //                 ->with('name')
    //                 ->andReturn($name);
    //             $mock->shouldReceive('getType')
    //                 ->andReturn($type);
    //             $mock->shouldReceive('getLowerSingular')
    //                 ->with($name)
    //                 ->once()
    //                 ->andReturn($name);
    //         });

    //         $result = $mock->getImportStatement();

    //         $this->assertIsString($result, 'The name should be a string.');
    //         $this->assertNotEmpty($result, 'The name shouldn\'t be empty.');
    //         $this->assertSame($expectedResult, $result, 'The name is incorrect.');
    //     }
    // }
}