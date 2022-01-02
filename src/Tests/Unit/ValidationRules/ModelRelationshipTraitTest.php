<?php

namespace Cruddy\Tests\Unit;

use Cruddy\Tests\TestTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;


class ModelRelationshipTraitTest extends TestCase
{
    // use TestTrait;

    // /**
    //  * A test to get the empty return value.
    //  *
    //  * @return void
    //  */
    // public function test_get_empty_return_value()
    // {
    //     $expectedResult = "return '';";

    //     $result = $this->getEmptyReturnValue();

    //     $this->assertSame($expectedResult, $result);
    // }

    // /**
    //  * A test to get the default return value.
    //  *
    //  * @return void
    //  */
    // public function test_get_default_return_value()
    // {
    //     $getRelationshipType = 'getRelationshipType';
    //     $expectedResult = '    return $this->' . $getRelationshipType . '();';

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($getRelationshipType) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('getRelationshipType')
    //             ->once()
    //             ->andReturn($getRelationshipType);
    //     });
        
    //     $result = $mock->getDefaultReturnValue();

    //     $this->assertSame($expectedResult, $result);
    // }

    // /**
    //  * A test for the can add to return value.
    //  *
    //  * @return void
    //  */
    // public function test_can_add_to_return_value()
    // {
    //     $callbacks = $this->getCallbacks();

    //     foreach ($callbacks as $callback) {
    //         $this->assertTrue($this->canAddToReturnValue('value', $callback));
    //     }

    //     $this->assertFalse($this->canAddToReturnValue('value', 'invalid-callback'));
    //     $this->assertFalse($this->canAddToReturnValue('', 'invalid-callback'));
    // }

    // /**
    //  * A test to get the callbacks.
    //  *
    //  * @return void
    //  */
    // public function test_get_callbacks()
    // {
    //     $expectedResult = [
    //         'getClassString',
    //         'getKeyString',
    //     ];

    //     $result = $this->getCallbacks();

    //     $this->assertSame($expectedResult, $result);
    // }

    // /**
    //  * A test to get the class string.
    //  *
    //  * @return void
    //  */
    // public function test_get_class_string()
    // {
    //     $class = 'fooBars';
    //     $firstClassMock = $this->partialMock(self::class, function (MockInterface $mock) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('isFirstClass')
    //             ->once()
    //             ->andReturn(true);
    //     });

    //     $nonFirstClassMock = $this->partialMock(self::class, function (MockInterface $mock) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('isFirstClass')
    //             ->once()
    //             ->andReturn(false);
    //     });

    //     $this->assertSame('FooBar::class', $firstClassMock->getClassString($class));
    //     $this->assertSame(', FooBar::class', $nonFirstClassMock->getClassString($class));
    // }

    // /**
    //  * A test to get the key string.
    //  *
    //  * @return void
    //  */
    // public function test_get_key_string()
    // {
    //     $value = 'value';
    //     $expectedResult = ", '$value'";

    //     $result = $this->getKeyString($value);

    //     $this->assertSame($expectedResult, $result);
    // }

    // /**
    //  * A test to get hte relationship array.
    //  *
    //  * @return void
    //  */
    // public function test_get_relationship_array()
    // {
    //     $getReturnValue = 'getReturnValue';
    //     $expectedResult = [
    //         '/**',
    //         ' * Get the associated {{ camelClassName }}.',
    //         ' */',
    //         'public function {{ camelClassName }}()',
    //         '{',
    //         $getReturnValue,
    //         '}',
    //     ];

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($getReturnValue) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('getReturnValue')
    //             ->once()
    //             ->andReturn($getReturnValue);
    //     });
        
    //     $result = $mock->getRelationshipArray();

    //     $this->assertSame($expectedResult, $result);
    // }
}