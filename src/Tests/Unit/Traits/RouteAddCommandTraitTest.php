<?php

namespace Cruddy\Tests\Unit\Traits;

use Cruddy\Tests\TestTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class RouteAddCommandTraitTest extends TestCase
{
    // use TestTrait;

    // /**
    //  * A test to get the resource route.
    //  *
    //  * @return void
    //  */
    // public function test_get_resource_route()
    // {
    //     $name = 'name';
    //     $lowerPlural = 'lowerPlural';
    //     $type = 'type';
    //     $expectedResult = "\n\n" . "// " . ucfirst($name) . " Resource\n"
    //         . "Route::" . $type . "('$lowerPlural', 'App\Http\Controllers\\" . ucfirst($name) . "Controller');";

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($name, $lowerPlural, $type) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('getResourceName')
    //             ->once()
    //             ->andReturn($name);
    //         $mock->shouldReceive('getLowerPlural')
    //             ->with($name)
    //             ->once()
    //             ->andReturn($lowerPlural);
    //         $mock->shouldReceive('getResourceType')
    //             ->once()
    //             ->andReturn($type);
    //     });

    //     $result = $mock->getResourceRoute();

    //     $this->assertSame($expectedResult, $result, 'The resource route does not match.');
    // }

    // /**
    //  * A test to get the lower plural for a string.
    //  *
    //  * @return void
    //  */
    // public function test_get_lower_plural()
    // {
    //     $singularNotLowerString = 'singularNotLowerString';
    //     $expectedResult = 'singularnotlowerstrings';

    //     $result = $this->getLowerPlural($singularNotLowerString);

    //     $this->assertSame($expectedResult, $result, 'The lowercase plural form of the variable does not match.');
    // }

    // /**
    //  * A test to get the resource name.
    //  *
    //  * @return void
    //  */
    // public function test_get_resource_name()
    // {
    //     $expectedResult = $name = 'name';
    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($name) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('argument')
    //             ->with('name')
    //             ->once()
    //             ->andReturn($name);
    //     });
        
    //     $result = $mock->getResourceName();

    //     $this->assertSame($expectedResult, $result, 'The resource name does not match.');
    // }

    // /**
    //  * A test to get the default resource type.
    //  *
    //  * @return void
    //  */
    // public function test_get_resource_type_default()
    // {
    //     $expectedResult = $this->defaultResourceType;
    //     $result = $this->getResourceType();

    //     $this->assertIsString($result, 'The resource type should be a string.');
    //     $this->assertNotEmpty($result, 'The resource type should not be empty.');
    //     $this->assertSame($expectedResult, $result, 'The default resource type does not match.');
    // }

    // /**
    //  * A test to get the resource type.
    //  *
    //  * @return void
    //  */
    // public function test_get_resource_type()
    // {
    //     $expectedResult = 'apiResource';

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('option')
    //             ->with('api')
    //             ->once()
    //             ->andReturn(true);
    //     });
        
    //     $result = $mock->getResourceType();

    //     $this->assertSame($expectedResult, $result, 'The default resource type does not match.');
    // }

    // /**
    //  * A test to get the route file name default.
    //  *
    //  * @return void
    //  */
    // public function test_route_file_name_default()
    // {
    //     $expectedResult = $this->defaultRouteFileName;
    //     $result = $this->getRouteFileName();

    //     $this->assertIsString($result, 'The default route file name should be a string.');
    //     $this->assertNotEmpty($result, 'The default route file name should not be empty.');
    //     $this->assertSame($expectedResult, $result, 'The default route file name does not match.');
    // }

    // /**
    //  * A test for getting the route file name.
    //  *
    //  * @return void
    //  */
    // public function test_route_file_name()
    // {
    //     $expectedResult = 'api';

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('option')
    //             ->with('api')
    //             ->once()
    //             ->andReturn(true);
    //     });
        
    //     $result = $mock->getRouteFileName();

    //     $this->assertSame($expectedResult, $result, 'The default resource type does not match.');

    // }

    // /**
    //  * A test to get the route file.
    //  *
    //  * @return void
    //  */
    // public function test_route_file()
    // {
    //     $name = 'name';
    //     $expectedResult = 'routes/' . $name .  '.php';

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($name) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('getRouteFileName')
    //             ->once()
    //             ->andReturn($name);
    //     });
        
    //     $result = $mock->getRouteFile();

    //     $this->assertSame($expectedResult, $result, 'The route file name does not match.');
    // }
}