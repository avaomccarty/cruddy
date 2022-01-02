<?php

namespace Cruddy\Tests\Unit\Traits;

use Cruddy\ModelRelationships\ModelRelationship;
use Cruddy\Tests\TestTrait;
use Cruddy\ForeignKeyDefinition;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class ModelMakeCommandTraitTest extends TestCase
{
    // use TestTrait;

    // /**
    //  * The inputs for the test.
    //  *
    //  * @var array
    //  */
    // protected $inputs = [];

    // public function setUp() : void
    // {
    //     parent::setUp();
    //     $this->inputs = $this->getMockColumns();
    // }

    // /**
    //  * A test to get the stub.
    //  *
    //  * @return void
    //  */
    // public function test_get_stub()
    // {
    //     $stubLocation = 'stub-location';
    //     $expectedResolvedStubLocation = $stubLocation . '/model.stub';
    //     $expectedOutput = 'output' . $stubLocation . '/model.stub';

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($stubLocation, $expectedResolvedStubLocation, $expectedOutput) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('getStubsLocation')
    //             ->andReturn($stubLocation);
    //         $mock->shouldReceive('resolveStubPath')
    //             ->with($expectedResolvedStubLocation)
    //             ->andReturn($expectedOutput);
    //     });

    //     $result = $mock->getStub();

    //     $this->assertIsString($result, 'The type should be a string.');
    //     $this->assertNotEmpty($result, 'The value shouldn\'t be empty.');
    //     $this->assertSame($expectedOutput, $result, 'The value is incorrect.');
    // }

    // /**
    //  * A test for the method calls within the buildClass method.
    //  *
    //  * @return void
    //  */
    // public function test_build_class_method_calls()
    // {
    //     $name = 'name';
    //     $stub = 'stub';
    //     $modelInputs = 'modelInputs';
    //     $foreignKey = new ForeignKeyDefinition([
    //         'inputType' => 'hasMany',
    //         'classes' => [],
    //         'keys' => [],
    //         'on' => 'users'
    //     ]);
    //     $keys = [
    //         $foreignKey
    //     ];

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($stub, $keys, $name, $modelInputs) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('getStubFile')
    //             ->once()
    //             ->andReturn($stub);
    //         $mock->shouldReceive('getKeys')
    //             ->once()
    //             ->andReturn($keys);

    //         foreach ($keys as $key) {
    //             $mock->shouldReceive('updateStubWithForeignKeys')
    //                 ->with($stub, $key)
    //                 ->once();
    //         }

    //         $mock->shouldReceive('replaceNamespace')
    //             ->with($stub, $name)
    //             ->once()
    //             ->andReturn($mock);
    //         $mock->shouldReceive('getModelInputs')
    //             ->once()
    //             ->andReturn($modelInputs);
    //         $mock->shouldReceive('replaceInStub')
    //             ->with($this->modelPlaceholders, $modelInputs, $stub)
    //             ->once()
    //             ->andReturn($mock);
    //         $mock->shouldReceive('replaceClass')
    //             ->with($stub, $name)
    //             ->once()
    //             ->andReturn($mock);
    //     });

    //     $mock->buildClass($name);
    // }

    // /**
    //  * A test for the method calls within the updateStubWithForeignKeys method.
    //  *
    //  * @return void
    //  */
    // public function test_update_stub_with_foreign_keys_method_calls()
    // {
    //     $stub = 'stub';
    //     $foreignKey = new ForeignKeyDefinition([
    //         'inputType' => 'hasMany',
    //         'classes' => [],
    //         'keys' => [],
    //         'on' => 'users'
    //     ]);
    //     $keys = [
    //         $foreignKey
    //     ];
    //     $getModelRelationship = new ModelRelationship($foreignKey);
    //     $getModelUseStatement = 'getModelUseStatement';
    //     $getModelRelationshipStub = 'getModelRelationshipStub';

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($stub, $keys, $getModelRelationship, $getModelUseStatement, $getModelRelationshipStub) {
    //         $mock->shouldAllowMockingProtectedMethods();

    //         foreach ($keys as $key) {
    //             $mock->shouldReceive('getModelRelationship')
    //                 ->with($key)
    //                 ->once()
    //                 ->andReturn($getModelRelationship);
    //             $mock->shouldReceive('getModelRelationshipStub')
    //                 ->with($getModelRelationship)
    //                 ->once()
    //                 ->andReturn($getModelRelationshipStub);
    //             $mock->shouldReceive('replaceInStub')
    //                 ->with($this->modelRelationshipPlaceholders, $getModelRelationshipStub . $this->modelRelationshipPlaceholders[0], $stub)
    //                 ->once()
    //                 ->andReturn();
    //             $mock->shouldReceive('getModelUseStatement')
    //                 ->with($getModelRelationship)
    //                 ->once()
    //                 ->andReturn($getModelUseStatement);
    //             $mock->shouldReceive('replaceInStub')
    //                 ->with($this->useStatementPlaceholders, $getModelUseStatement . $this->useStatementPlaceholders[0], $stub)
    //                 ->once()
    //                 ->andReturn();
    //         }
    //     });

    //     $mock->updateStubWithForeignKeys($stub, $foreignKey);
    // }

    // /**
    //  * A test to get the model inputs.
    //  *
    //  * @return void
    //  */
    // public function test_get_model_inputs()
    // {
    //     $inputs = ['inputs'];
    //     $expectedResult = $inputsString = 'inputsString';

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($inputs, $inputsString) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('getInputs')
    //             ->once()
    //             ->andReturn($inputs);
    //         $mock->shouldReceive('getModelInputsString')
    //             ->with($inputs)
    //             ->once()
    //             ->andReturn($inputsString);
    //     });

    //     $result = $mock->getModelInputs();

    //     $this->assertSame($expectedResult, $result);
    // }

    // /**
    //  * A test to get the model inputs as a string.
    //  *
    //  * @return void
    //  */
    // public function test_get_model_inputs_string_when_edit_or_show()
    // {
    //     $inputs = $this->getMockColumns();
    //     $expectedResult = '';
    //     $isEditOrShow = true;

    //     foreach ($inputs as $input) {
    //         $expectedResult .= $input->name;
    //     }

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($inputs, $isEditOrShow, $expectedResult) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('isVueEditOrShow')
    //             ->once()
    //             ->andReturn($isEditOrShow);

    //         foreach ($inputs as $input) {
    //             $mock->shouldReceive('getInputString')
    //                 ->with($input, $isEditOrShow)
    //                 ->once()
    //                 ->andReturn($input->name);
    //         }

    //         $mock->shouldReceive('removeEndOfLineFormatting')
    //             ->with($expectedResult)
    //             ->once()
    //             ->andReturn($expectedResult);
    //     });

    //     $result = $mock->getModelInputsString($inputs);

    //     $this->assertSame($expectedResult, $result);
    // }

    // /**
    //  * A test to get the model inputs as a string.
    //  *
    //  * @return void
    //  */
    // public function test_get_model_inputs_string_when_not_edit_or_show()
    // {
    //     $inputs = $this->getMockColumns();
    //     $expectedResult = '';
    //     $isEditOrShow = false;

    //     foreach ($inputs as $input) {
    //         $expectedResult .= $input->name;
    //     }

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($inputs, $isEditOrShow, $expectedResult) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('isVueEditOrShow')
    //             ->once()
    //             ->andReturn($isEditOrShow);

    //         foreach ($inputs as $input) {
    //             $mock->shouldReceive('getInputString')
    //                 ->with($input, $isEditOrShow)
    //                 ->once()
    //                 ->andReturn($input->name);
    //         }

    //         $mock->shouldReceive('removeEndOfLineFormatting')
    //             ->with($expectedResult)
    //             ->once()
    //             ->andReturn($expectedResult);
    //     });

    //     $result = $mock->getModelInputsString($inputs);

    //     $this->assertSame($expectedResult, $result);
    // }
}