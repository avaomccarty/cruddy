<?php

namespace Cruddy\Tests\Unit\Traits;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\ConfigTrait;
use Cruddy\Traits\Stubs\InputTrait;
use Illuminate\Support\Facades\File;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class CommandTraitTest extends TestCase
{
    use CommandTrait, InputTrait, ConfigTrait, TestTrait;

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
     * A test for getting the name string.
     *
     * @return void
     */
    public function test_get_name_string()
    {
        $expectedResult = $name = 'name';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($name) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('argument')
                ->with('name')
                ->once()
                ->andReturn($name);
        });
        
        $result = $mock->getNameString();

        $this->assertSame($expectedResult, $result, 'The name is incorrect.');
    }

    /**
     * A test for getting the inputs.
     *
     * @return void
     */
    public function test_get_inputs()
    {
        $expectedResult = $inputs = ['inputs'];

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($inputs) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('argument')
                ->with('inputs')
                ->once()
                ->andReturn($inputs);
        });
        
        $result = $mock->getInputs();

        $this->assertSame($expectedResult, $result, 'The default input type is incorrect.');
    }

    /**
     * A test to get the inputs string with a submit needed.
     *
     * @return void
     */
    public function test_get_inputs_string_with_submit()
    {
        $inputs = $this->getMockColumns();
        $expectedResult = '';
        $getInputFile = '-getInputFile';

        foreach ($inputs as $input) {
            $expectedResult .= $input['name'];
        }

        $expectedResult .= $getInputFile;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($inputs, $getInputFile, $expectedResult) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getInputs')
                ->once()
                ->andReturn($inputs);
            $mock->shouldReceive('isVueEditOrShow')
                ->andReturn(false);

            foreach ($inputs as $input) {
                $mock->shouldReceive('getInputString')
                    ->with($input, false)
                    ->once()
                    ->andReturn($input['name']);
            }

            $mock->shouldReceive('getInputFile')
                ->with('submit')
                ->once()
                ->andReturn($getInputFile);

            $mock->shouldReceive('replaceInStub')
                ->with($this->valuePlaceholders, 'Submit', $getInputFile)
                ->once();
        });

        $result = $mock->getInputsString(true);

        $this->assertIsString($result, 'The result should be a string.');
        $this->assertNotEmpty($result, 'The inputs string should not be empty when there are inputs.');
        $this->assertSame($expectedResult, $result, 'The result is not correct.');
    }

    /**
     * A test for should add value to input when needs Vue frontend needed and is edit/show.
     *
     * @return void
     */
    public function test_should_add_value_to_input_when_does_not_need_vue_frontend_and_is_edit_or_show()
    {
        $isEditOrShow = true;
        $needsVueFrontend = false;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($isEditOrShow, $needsVueFrontend) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('isEditOrShow')
                ->once()
                ->andReturn($isEditOrShow);
            $mock->shouldReceive('needsVueFrontend')
                ->once()
                ->andReturn($needsVueFrontend);
        });
        
        $result = $mock->shouldAddValueToInput();

        $this->assertTrue($result);
    }

    /**
     * A test for should add value to input when needs Vue frontend needed and is not edit/show.
     *
     * @return void
     */
    public function test_should_add_value_to_input_when_needs_vue_frontend_and_is_edit_or_show()
    {
        $isEditOrShow = true;
        $needsVueFrontend = true;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($isEditOrShow, $needsVueFrontend) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('isEditOrShow')
                ->once()
                ->andReturn($isEditOrShow);
            $mock->shouldReceive('needsVueFrontend')
                ->once()
                ->andReturn($needsVueFrontend);
        });
        
        $result = $mock->shouldAddValueToInput();

        $this->assertFalse($result);
    }

    /**
     * A test for should add value to input when does not need Vue frontend needed and is edit/show.
     *
     * @return void
     */
    public function test_should_add_value_to_input_when_is_not_edit_or_show()
    {
        $isEditOrShow = false;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($isEditOrShow) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('isEditOrShow')
                ->once()
                ->andReturn($isEditOrShow);
            $mock->shouldReceive('needsVueFrontend')
                ->never();
        });
        
        $result = $mock->shouldAddValueToInput();

        $this->assertFalse($result);
    }

    /**
     * A test for getting the camel case singular version of the value.
     *
     * @return void
     */
    public function test_get_camel_case_singular()
    {
        $value = 'TestVariableNames';
        $expectedResult = 'testVariableName';

        $result = $this->getCamelCaseSingular($value);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test for getting the camel case plural version of the value.
     *
     * @return void
     */
    public function test_get_camel_case_plural()
    {
        $value = 'TestVariableName';
        $expectedResult = 'testVariableNames';

        $result = $this->getCamelCasePlural($value);

        $this->assertSame($expectedResult, $result);
    }
}