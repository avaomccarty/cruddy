<?php

namespace Cruddy\Tests\Unit\Traits\Stubs;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\Stubs\InputTrait;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\File;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class InputTraitTest extends TestCase
{
    use CommandTrait, InputTrait, TestTrait;

    /**
     * A test for determining if a stub needs a submit input when not needing input.
     *
     * @return void
     */
    public function test_type_needs_submit_input_when_submit_input_not_needed()
    {
        $types = [
            'index',
            'show',
        ];

        foreach ($types as $type) {
            $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type) {
                $mock->shouldAllowMockingProtectedMethods();
                $mock->shouldReceive('getType')
                    ->once()
                    ->andReturn($type);
            });
            
            $result = $mock->typeNeedsSubmitInput();

            $this->assertFalse($result);
        }
    }

    /**
     * A test for determining if a stub needs a submit input when needing input.
     *
     * @return void
     */
    public function test_type_needs_submit_input_when_submit_input_needed()
    {
        $types = [
            'create',
            'edit',
        ];

        foreach ($types as $type) {
            $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type) {
                $mock->shouldAllowMockingProtectedMethods();
                $mock->shouldReceive('getType')
                    ->once()
                    ->andReturn($type);
            });
            
            $result = $mock->typeNeedsSubmitInput();

            $this->assertTrue($result);
        }
    }

    /**
     * A test getting the default input.
     *
     * @return void
     */
    public function test_get_default_input()
    {
        $expectedResult = 'text';
        $result = $this->getDefaultInput();

        $this->assertSame($expectedResult, $result, 'The default input type is incorrect.');
    }

    /**
     * A test to get the inputs string without inputs.
     *
     * @return void
     */
    public function test_get_inputs_string_without_inputs()
    {
        $mock = $this->partialMock(self::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getInputs')
                ->once()
                ->andReturn([]);
        });

        $result = $mock->getInputsString();

        $this->assertIsString($result, 'The result should be a string.');
        $this->assertEmpty($result, 'The inputs string should be empty when there are no inputs.');
    }

    /**
     * A test to get the inputs string without a submit needed.
     *
     * @return void
     */
    public function test_get_inputs_string_without_submit()
    {
        $inputs = $this->getMockColumns();
        $expectedResult = '';

        foreach ($inputs as $input) {
            $expectedResult .= $input['name'];
        }

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($inputs) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getInputs')
                ->once()
                ->andReturn($inputs);

            foreach ($inputs as $input) {
                $mock->shouldReceive('getInputString')
                    ->once()
                    ->andReturn($input['name']);
            }
        });

        $result = $mock->getInputsString(false);

        $this->assertIsString($result, 'The result should be a string.');
        $this->assertNotEmpty($result, 'The inputs string should not be empty when there are inputs.');
        $this->assertSame($expectedResult, $result, 'The result is not correct.');
    }

    /**
     * A test for getting an input with default value.
     *
     * @return void
     */
    public function test_get_input_with_default_value()
    {
        $expectedResult = $input = 'input';
        $inputs = [
            $input => ''
        ];

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($input, $inputs) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getInputDefaults')
                ->once()
                ->andReturn($inputs);
            $mock->shouldReceive('getDefaultForInputType')
                ->with($input)
                ->once()
                ->andReturn($input);
        });

        $result = $mock->getInput($input);

        $this->assertSame($expectedResult, $result, 'The result is not correct.');
    }

    /**
     * A test for getting an input without default value.
     *
     * @return void
     */
    public function test_get_input_without_default_value()
    {
        $expectedResult = $input = 'input';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($input) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getInputDefaults')
                ->once()
                ->andReturn([]);
            $mock->shouldReceive('getDefaultInput')
                ->once()
                ->andReturn($input);
        });

        $result = $mock->getInput($input);

        $this->assertSame($expectedResult, $result, 'The result is not correct.');
    }

    /**
     * A test to get the input file.
     *
     * @return void
     */
    public function test_get_input_file()
    {
        $type = 'type';
        $inputStub = 'inputStub';
        $expectedResult = $file = 'file';

        File::shouldReceive('get')
            ->with($inputStub)
            ->once()
            ->andReturn($file);

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type, $inputStub) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getInputStub')
                ->with($type)
                ->once()
                ->andReturn($inputStub);
        });

        $result = $mock->getInputFile($type);

        $this->assertSame($expectedResult, $result, 'The file is not correct.');
    }

    /**
     * A test for getting the input string.
     *
     * @return void
     */
    public function test_get_input_string()
    {
        $input = $this->getMockColumns()[0];
        $originalInputString = $expectedResult = $inputString = 'input-string-';
        $replaceString = 'string-';
        $extraInputString = 'extra-input-string-';

        foreach ($this->modelNamePlaceholders as $placeholder) {
            $inputString .= $placeholder;
            $expectedResult .= $replaceString;
        }

        foreach ($this->namePlaceholders as $placeholder) {
            $inputString .= $placeholder;
            $expectedResult .= $input['name'];
        }

        foreach ($this->dataPlaceholders as $placeholder) {
            $inputString .= $placeholder;
            $expectedResult .= $extraInputString;
        }

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($input, $inputString, $replaceString, $extraInputString) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getInputAsString')
                ->with($input)
                ->once()
                ->andReturn($inputString);
            $mock->shouldReceive('getReplaceString')
                ->with($input, true)
                ->once()
                ->andReturn($replaceString);
            $mock->shouldReceive('getExtraInputInfo')
                ->with($input)
                ->once()
                ->andReturn($extraInputString);
        });
     
        $result = $mock->getInputString($input, true);

        $this->assertNotSame($originalInputString, $inputString, 'The input string should have been updated.');
        $this->assertSame($expectedResult, $result, 'The string for the input is incorrect.');
    }

    /**
     * A test getting the replace string for an edit/show file type with a Vue frontend.
     *
     * @return void
     */
    public function test_get_replace_string_for_an_edit_or_show_file_with_vue()
    {
        $input = $this->getMockColumns()[0];
        $expectedResult = 'item.' . $input['name'];

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($input) {
            $mock->shouldAllowMockingProtectedMethods();
        });
        
        $result = $mock->getReplaceString($input, true);

        $this->assertSame($expectedResult, $result, 'The replace string for the input is incorrect.');
    }

    /**
     * A test getting the replace string for an edit/show file type without a Vue frontend.
     *
     * @return void
     */
    public function test_get_replace_string_for_an_edit_or_show_file_without_vue()
    {
        $input = $this->getMockColumns()[0];
        $expectedResult = $input['name'];

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($input) {
            $mock->shouldAllowMockingProtectedMethods();
        });
        
        $result = $mock->getReplaceString($input, false);

        $this->assertSame($expectedResult, $result, 'The replace string for the input is incorrect.');
    }

    /**
     * A test getting the replace string for a non edit/show file type with a Vue frontend.
     *
     * @return void
     */
    public function test_get_replace_string_for_a_non_edit_or_show_file_with_vue()
    {
        $input = $this->getMockColumns()[0];
        $expectedResult = $input['name'];

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($input) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('needsVueFrontend')
                ->never();
        });
        
        $result = $mock->getReplaceString($input);

        $this->assertSame($expectedResult, $result, 'The replace string for the input is incorrect.');
    }

    /**
     * A test getting the input as a string.
     *
     * @return void
     */
    public function test_get_input_as_string()
    {
        $input = $this->getMockColumns()[0];
        $expectedResult = $inputFile = 'inputFile';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($input, $inputFile) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getInputFile')
                ->with($input['type'])
                ->once()
                ->andReturn($inputFile);
            $mock->shouldReceive('addEndOfLineFormatting')
                ->with($inputFile)
                ->once();
        });

        $result = $mock->getInputAsString($input);

        $this->assertSame($expectedResult, $result, 'The replace string for the input is incorrect.');
    }

    /**
     * A test for checking if an edit type is an edit/show type.
     *
     * @return void
     */
    public function test_is_edit_or_show_for_edit_type()
    {
        $type = 'edit';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
        });
        
        $result = $mock->isEditOrShow();

        $this->assertTrue($result, 'The edit type should return true when checking if the file is edit or show.');
    }

    /**
     * A test for checking if a show type is an edit/show type.
     *
     * @return void
     */
    public function test_is_edit_or_show_for_show_type()
    {
        $type = 'show';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
        });
        
        $result = $mock->isEditOrShow();

        $this->assertTrue($result, 'The show type should return true when checking if the file is edit or show.');
    }

    /**
     * A test getting the input stub.
     *
     * @return void
     */
    public function test_get_input_stub()
    {
        $input = 'input';
        $stubLocation = 'stubLocation';
        $expectedResult = 'expectedResult';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($input, $stubLocation, $expectedResult) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getInputStubLocation')
                ->with($input)
                ->once()
                ->andReturn($stubLocation);
            $mock->shouldReceive('resolveStubPath')
                ->with($stubLocation)
                ->once()
                ->andReturn($expectedResult);
        });

        $result = $mock->getInputStub($input);

        $this->assertSame($expectedResult, $result, 'The input stub is incorrect.');
    }

    /**
     * A test getting the stub location.
     *
     * @return void
     */
    public function test_get_stub_location()
    {
        $input = 'input';
        $inputString = 'inputString';
        $frontendScaffolding = 'frontendScaffolding';
        $stubsLocation = 'stubsLocation';
        $expectedResult = $stubsLocation . '/views/' . $frontendScaffolding  . '/inputs/' . $inputString . '.stub';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($input, $frontendScaffolding, $stubsLocation, $inputString) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getFrontendScaffoldingName')
                ->once()
                ->andReturn($frontendScaffolding);
            $mock->shouldReceive('getStubsLocation')
                ->once()
                ->andReturn($stubsLocation);
            $mock->shouldReceive('getInput')
                ->with($input)
                ->once()
                ->andReturn($inputString);
        });

        $result = $mock->getInputStubLocation($input);

        $this->assertSame($expectedResult, $result, 'The stub location is incorrect.');
    }

    /**
     * A test getting the extra input information.
     *
     * @return void
     */
    public function test_get_extra_input_info()
    {
        $input = $this->getMockColumns()[0];
        $value1 = 'value1';
        $value2 = 'value2';
        $expectedResult = '';
        $mapppingArray = [
            [
                $this->shouldAddExtraInput => true,
                $this->valueToAdd => $value1
            ],
            [
                $this->shouldAddExtraInput => false,
                $this->valueToAdd => $value2
            ]
        ];

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($input, $mapppingArray, $value1, $value2) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getExtraInputInfoMapping')
                ->with($input)
                ->once()
                ->andReturn($mapppingArray);
            $mock->shouldReceive('addExtraInputInfo')
                ->with('', $value1)
                ->once();
            $mock->shouldReceive('addExtraInputInfo')
                ->with('', $value2)
                ->never();
        });

        $result = $mock->getExtraInputInfo($input);

        $this->assertSame($expectedResult, $result, 'The value returned is incorrect.');
    }

    /**
     * A test getting the extra input mapping.
     *
     * @return void
     */
    public function test_get_extra_input_mapping()
    {
        $input = $this->getMockColumns()[0];
        $input->unsigned = true;
        $shouldAddValueToInput = true;
        $getValueFromColumn = 'getValueFromColumn';
        $isShowType = true;
        $shouldInputBeChecked = true;
        $expectedResult = [
            [
                $this->shouldAddExtraInput => $shouldAddValueToInput,
                $this->valueToAdd => $getValueFromColumn
            ],
            [
                $this->shouldAddExtraInput => $isShowType,
                $this->valueToAdd => $this->inputDisabledString
            ],
            [
                $this->shouldAddExtraInput => $input->unsigned,
                $this->valueToAdd => $this->unsignedExtraInputInfo
            ],
            [
                $this->shouldAddExtraInput => $shouldInputBeChecked,
                $this->valueToAdd => $this->inputCheckedString
            ],
        ];

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($input, $shouldAddValueToInput, $getValueFromColumn, $isShowType, $shouldInputBeChecked) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('shouldAddValueToInput')
                ->once()
                ->andReturn($shouldAddValueToInput);
            $mock->shouldReceive('getValueFromColumn')
                ->with($input)
                ->once()
                ->andReturn($getValueFromColumn);
            $mock->shouldReceive('isShowType')
                ->once()
                ->andReturn($isShowType);
            $mock->shouldReceive('shouldInputBeChecked')
                ->with($input)
                ->once()
                ->andReturn($shouldInputBeChecked);
        });

        $result = $mock->getExtraInputInfoMapping($input);

        $this->assertSame($expectedResult, $result, 'The value returned is incorrect.');
    }

    /**
     * A test getting the value from the column.
     *
     * @return void
     */
    public function test_get_value_from_column()
    {
        $input = $this->getMockColumns()[0];
        $expectedResult = 'value="{{ $name->' . $input['name'] . ' }}"';

        $result = $this->getValueFromColumn($input);

        $this->assertSame($expectedResult, $result, 'The value returned is incorrect.');
    }

    /**
     * A test for adding the extra input info.
     *
     * @return void
     */
    public function test_add_extra_input_info()
    {
        $value = 'value-';
        $valueToAdd = 'valueToAdd-';
        $expectedValue = $value . $valueToAdd;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($value) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('prepString')
                ->with($value)
                ->once();
        });
        
        $mock->addExtraInputInfo($value, $valueToAdd);

        $this->assertSame($expectedValue, $value, 'The value is incorrect.');
    }

    /**
     * A test to prep the string.
     *
     * @return void
     */
    public function test_prep_string()
    {
        $value = 'value';
        $expectedValue = $value . ' ';

        $this->prepString($value);

        $this->assertSame($expectedValue, $value, 'The value is incorrect.');
    }

    /**
     * A test for should add value to input when a show/edit type used.
     *
     * @return void
     */
    public function test_should_add_value_to_input_when_not_edit_or_show()
    {
        $mock = $this->partialMock(self::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods();
        });
        
        $result = $mock->shouldAddValueToInput();

        $this->assertFalse($result);
    }

    /**
     * A test for should add value to input when Vue frontend needed.
     *
     * @return void
     */
    public function test_should_add_value_to_input_when_needs_vue_frontend()
    {
        $mock = $this->partialMock(self::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods();
        });
        
        $result = $mock->shouldAddValueToInput();

        $this->assertFalse($result);
    }

    /**
     * A test for determining if the type is 'show' for the 'show' type.
     *
     * @return void
     */
    public function test_is_show_type_for_show_type()
    {
        $mock = $this->partialMock(self::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn('show');
        });
        
        $result = $mock->isShowType();

        $this->assertTrue($result);
    }

    /**
     * A test for determining if the type is 'show' for a non-show type.
     *
     * @return void
     */
    public function test_is_show_type_for_not_show_type()
    {
        $types = array_filter($this->getDefaultTypes(), function ($type) {
            return $type !== 'show';
        });

        foreach ($types as $type) {
            $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type) {
                $mock->shouldAllowMockingProtectedMethods();
                $mock->shouldReceive('getType')
                    ->once()
                    ->andReturn($type);
            });
            
            $result = $mock->isShowType();
    
            $this->assertFalse($result);
        }
    }

    /**
     * A test for the should be checked for an input that does not use a checkbox.
     *
     * @return void
     */
    public function test_should_input_be_checked_when_not_boolean_column()
    {
        $column = new ColumnDefinition([
            'type' => 'string'
        ]);

        $result = $this->shouldInputBeChecked($column);
        
        $this->assertFalse($result);
    }

    /**
     * A test for the should be checked for an input with a default of unchecked.
     *
     * @return void
     */
    public function test_should_input_be_checked_when_boolean_column_default_unchecked()
    {
        $column = new ColumnDefinition([
            'type' => 'boolean',
            'default' => 0
        ]);

        $result = $this->shouldInputBeChecked($column);
        
        $this->assertFalse($result);
    }

    /**
     * A test for the should be checked for an input with a default of checked.
     *
     * @return void
     */
    public function test_should_input_be_checked_when_boolean_column_default_checked()
    {
        $column = new ColumnDefinition([
            'type' => 'boolean',
            'default' => 1
        ]);

        $result = $this->shouldInputBeChecked($column);
        
        $this->assertTrue($result);
    }

    /**
     * A test to determine if a boolean column functions as a boolean.
     *
     * @return void
     */
    public function test_is_boolean_column_on_boolean_column()
    {
        $column = new ColumnDefinition([
            'type' => 'boolean'
        ]);

        $result = $this->isBooleanColumn($column);
        
        $this->assertTrue($result);
    }

    /**
     * A test to determine if a tinyInteger column functions as a boolean.
     *
     * @return void
     */
    public function test_is_boolean_column_on_tiny_integer_column()
    {
        $column = new ColumnDefinition([
            'type' => 'tinyInteger'
        ]);

        $result = $this->isBooleanColumn($column);
        
        $this->assertTrue($result);
    }

    /**
     * A test to determine if a non-boolean column functions as a boolean.
     *
     * @return void
     */
    public function test_is_boolean_column_on_other_column_type()
    {
        $columns = array_filter($this->getMockColumns(), function ($column) {
            return $column['type'] !== 'boolean' && $column['type'] !== 'tinyInteger';
        });

        foreach ($columns as $column) {
            $result = $this->isBooleanColumn($column);

            $this->assertFalse($result);
        }
    }

    /**
     * A test to get the controller input string for a non-ID column.
     *
     * @return void
     */
    public function test_get_controller_input_string_for_non_id_column()
    {
        $name = 'name';
        $input = new ColumnDefinition([
            'name' => $name
        ]);
        $endOfLine = 'endOfLine';
        $expectedResult = "'" . $input->name . "'" . ' => $request->' . $input->name . "," . $endOfLine;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($input, $endOfLine) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('isIdColumn')
                ->with($input)
                ->once()
                ->andReturn(false);
            $mock->shouldReceive('getEndOfLine')
                ->once()
                ->andReturn($endOfLine);
        });
        
        
        $result = $mock->getControllerInputString($input);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the controller input string for an ID column.
     *
     * @return void
     */
    public function test_get_controller_input_string_for_id_column()
    {
        $name = 'id';
        $input = new ColumnDefinition([
            'name' => $name
        ]);
        $endOfLine = 'endOfLine';
        $expectedResult = '';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($input, $endOfLine) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('isIdColumn')
                ->with($input)
                ->once()
                ->andReturn(true);
            $mock->shouldReceive('getEndOfLine')
                ->never();
        });
        
        
        $result = $mock->getControllerInputString($input);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the model input string for a non-ID column.
     *
     * @return void
     */
    public function test_get_model_input_string_for_non_id_column()
    {
        $name = 'name';
        $input = new ColumnDefinition([
            'name' => $name
        ]);
        $endOfLine = 'endOfLine';
        $expectedResult = "'" . $input->name . "'," . $endOfLine;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($input, $endOfLine) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('isIdColumn')
                ->with($input)
                ->once()
                ->andReturn(false);
            $mock->shouldReceive('getEndOfLine')
                ->once()
                ->andReturn($endOfLine);
        });
        
        
        $result = $mock->getModelInputString($input);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the model input string for an ID column.
     *
     * @return void
     */
    public function test_get_model_input_string_for_id_column()
    {
        $name = 'id';
        $input = new ColumnDefinition([
            'name' => $name
        ]);
        $endOfLine = 'endOfLine';
        $expectedResult = '';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($input, $endOfLine) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('isIdColumn')
                ->with($input)
                ->once()
                ->andReturn(true);
            $mock->shouldReceive('getEndOfLine')
                ->never();
        });
        
        
        $result = $mock->getModelInputString($input);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test for if an input is for an ID column when it is an ID column.
     *
     * @return void
     */
    public function test_is_id_column_for_id_column()
    {
        $column = new ColumnDefinition([
            'name' => 'id'
        ]);

        $result = $this->isIdColumn($column);

        $this->assertTrue($result);
    }

    /**
     * A test for if an input is for an ID column when it is not an ID column.
     *
     * @return void
     */
    public function test_is_id_column_for_non_id_column()
    {
        $column = new ColumnDefinition([
            'name' => 'not-id'
        ]);

        $result = $this->isIdColumn($column);

        $this->assertFalse($result);
    }
}