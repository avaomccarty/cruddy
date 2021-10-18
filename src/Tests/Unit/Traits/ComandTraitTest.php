<?php

namespace Cruddy\Tests\Unit\Traits;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\ConfigTrait;
use Cruddy\Traits\Stubs\InputTrait;
use Cruddy\Traits\Stubs\ModelTrait;
use Illuminate\Support\Facades\File;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class CommandTraitTest extends TestCase
{
    use CommandTrait, InputTrait, ModelTrait, ConfigTrait, TestTrait;

    /**
     * The acceptable types.
     *
     * @var array
     */
    protected $types = [
        'create',
        'index',
        'show',
        'edit',
    ];

    /**
     * A test to get the table name when the argument method exists and null returned.
     *
     * @return void
     */
    public function test_get_table_name_without_return_value()
    {
        $mock = $this->partialMock(self::class, function (MockInterface $mock) {
            $mock->shouldReceive('argument')
                ->once()
                ->with('table')
                ->andReturn(null);
        });

        $result = $mock->getTableName();

        $this->assertIsString($result, 'The type should be a string.');
        $this->assertEmpty($result, 'The table name value should be empty.');
    }

    /**
     * A test to get the table name when the argument method exists and string returned.
     *
     * @return void
     */
    public function test_get_table_name_with_return_value()
    {
        $expectedReturnValue = 'test-return-value';
        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($expectedReturnValue) {
            $mock->shouldReceive('argument')
                ->once()
                ->with('table')
                ->andReturn($expectedReturnValue);
        });

        $result = $mock->getTableName();

        $this->assertIsString($result, 'The type should be a string.');
        $this->assertNotEmpty($result, 'The type should not be empty.');
        $this->assertSame($expectedReturnValue, $result, 'The table name value is incorrect.');
    }

    /**
     * A test to get the lower singular form of a string.
     *
     * @return void
     */
    public function test_get_lower_singular_string()
    {
        $tests = [
            'CATS' => 'cat',
            'ElePhanTs' => 'elephant',
            'Buses' => 'bus',
            'Tests' => 'test',
            'Faxes' => 'fax',
            'ditches' => 'ditch',
            'WISHES' => 'wish',
            'bus_stops' => 'busstop',
            'food-markets' => 'foodmarket',
            'emPTyRoOms' => 'emptyroom',
            'USERS' => 'user',
            'users' => 'user',
        ];

        foreach ($tests as $plural => $singular) {
            $result = $this->getLowerSingular($plural);

            $this->assertIsString($result, 'The type should be a string.');
            $this->assertNotEmpty($result, 'The type should not be empty.');
            $this->assertSame($singular, $result, 'The singular lowercase form of the string is incorrect.');
        }
    }

    /**
     * A test to get the studly singular form of a string.
     *
     * @return void
     */
    public function test_get_studly_singular_string()
    {
        $tests = [
            'CATS' => 'CAT',
            'Elephants' => 'Elephant',
            'Buses' => 'Bus',
            'Tests' => 'Test',
            'Faxes' => 'Fax',
            'ditches' => 'Ditch',
            'wishes' => 'Wish',
            'bus_stops' => 'BusStop',
            'food-markets' => 'FoodMarket',
            'emptyRooms' => 'EmptyRoom',
            'USERS' => 'USER',
            'users' => 'User',
        ];

        foreach ($tests as $plural => $singular) {
            $result = $this->getStudlySingular($plural);

            $this->assertIsString($result, 'The type should be a string.');
            $this->assertNotEmpty($result, 'The type should not be empty.');
            $this->assertSame($singular, $result, 'The singular lowercase form of the word is incorrect.');
        }
    }

    /**
     * A test to get the type when a valid type is used.
     *
     * @return void
     */
    public function test_get_type_with_valid_types_used()
    {
        foreach ($this->types as $type) {
            $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type) {
                $mock->shouldReceive('argument')
                    ->with('type')
                    ->andReturn($type);
            });
            
            $result = $mock->getType();

            $this->assertIsString($result, 'The type should be a string.');
            $this->assertNotEmpty($result, 'The type should not be empty.');
            $this->assertSame($type, $result, 'The type is incorrect.');
        }
    }

    /**
     * A test to get the type when a valid type is used.
     *
     * @return void
     */
    public function test_get_type_with_valid_types_used_as_property()
    {
        foreach ($this->types as $type) {
            $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type) {
                $mock->shouldReceive('argument')
                    ->with('type')
                    ->andReturn($type);
            });
            
            $mock->types = $this->types;
            $result = $mock->getType();

            $this->assertIsString($result, 'The type should be a string.');
            $this->assertNotEmpty($result, 'The type should not be empty.');
            $this->assertSame($type, $result, 'The type is incorrect.');
        }
    }

    /**
     * A test to get the type when a invalid type is used.
     *
     * @return void
     */
    public function test_get_type_with_invalid_type_for_types_as_property()
    {
        $invalidType = 'invalid-type';

        foreach ($this->types as $type) {
            $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($invalidType) {
                $mock->shouldReceive('argument')
                    ->with('type')
                    ->andReturn($invalidType);
            });

            $mock->types = $this->types;
            $result = $mock->getType();

            $this->assertIsString($result, 'The type should be a string.');
            $this->assertNotEmpty($result, 'The type should not be empty.');
            $this->assertSame($this->types[0], $result, 'The type is incorrect.');
        }
    }

    /**
     * A test to get the type when a invalid type is used.
     *
     * @return void
     */
    public function test_get_type_with_invalid_type_for_default_types()
    {
        $invalidType = 'invalid-type';
        $expectedResult = 'result';

        foreach ($this->types as $type) {
            $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($invalidType, $expectedResult) {
                $mock->shouldAllowMockingProtectedMethods();
                $mock->shouldReceive('argument')
                    ->with('type')
                    ->andReturn($invalidType);

                $mock->shouldReceive('getDefaultTypes')
                    ->andReturn([$expectedResult]);
            });

            // Unset the types so the default types array is used
            unset($mock->types);

            $result = $mock->getType();

            $this->assertIsString($result, 'The type should be a string.');
            $this->assertNotEmpty($result, 'The type should not be empty.');
            $this->assertSame($expectedResult, $result, 'The type is incorrect.');
        }
    }

    /**
     * A test to get the deault types.
     *
     * @return void
     */
    public function test_get_default_types()
    {
        $expectedTypes = [
            'index',
            'create',
            'show',
            'edit',
        ];

        $result = $this->getDefaultTypes();

        $this->assertIsArray($result);
        $this->assertEquals($result, $expectedTypes);
    }

    /**
     * A test to get the stub.
     *
     * @return void
     */
    public function test_get_stub()
    {
        $frontendScaffolding = 'frontend-scaffolding';
        $stubsLocation = 'stubs-location';
        $type = 'index';
        $expectedResult = 'test';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($frontendScaffolding, $stubsLocation, $type, $expectedResult) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getFrontendScaffoldingName')
                ->once()
                ->andReturn($frontendScaffolding);

            $mock->shouldReceive('getStubsLocation')
                ->once()
                ->andReturn($stubsLocation);
                
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
                
            $mock->shouldReceive('resolveStubPath')
                ->with($stubsLocation . '/views/' . $frontendScaffolding  . '/' . $type . '.stub')
                ->once()
                ->andReturn($expectedResult);
        });

        $result = $mock->getStub();

        $this->assertIsString($result, 'The type should be a string.');
        $this->assertNotEmpty($result, 'The type should not be empty.');
        $this->assertSame($expectedResult, $result, 'The stub is incorrect.');
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
        $expectedResult = dirname(dirname(dirname(__DIR__))) . '/Commands/' . $stub;

        File::shouldReceive('exists')
            ->once()
            ->andReturn(false);

        $result = $this->resolveStubPath($stub);

        $this->assertIsString($result, 'The type should be a string.');
        $this->assertNotEmpty($result, 'The type should not be empty.');
        $this->assertSame($expectedResult, $result, 'The stub is incorrect.');
    }
}