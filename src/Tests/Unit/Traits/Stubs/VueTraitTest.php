<?php

namespace Cruddy\Tests\Unit\Traits\Stubs;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\Stubs\FormTrait;
use Cruddy\Traits\Stubs\ModelTrait;
use Cruddy\Traits\Stubs\VariableTrait;
use Cruddy\Traits\Stubs\VueTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class VueTraitTest extends TestCase
{
    use CommandTrait, ModelTrait, FormTrait, VueTrait, VariableTrait, TestTrait;

    /**
     * A test to replace Vue data.
     *
     * @return void
     */
    public function test_replace_vue_data()
    {
        $inputs = $this->getMockColumns();
        $search = 'search';
        $baseStub = '-in-stub';
        $stub = $originalStub = $search . $baseStub;
        $expectedStub = str_repeat($search, count($inputs)) . $baseStub;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($search, $inputs) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('argument')
                ->with('inputs')
                ->once()
                ->andReturn($inputs);

            foreach ($inputs as $input) {
                $mock->shouldReceive('getVueDataString')
                    ->with($input)
                    ->andReturn($search);
            }

            $mock->shouldReceive('needsVueFrontend')
                ->once()
                ->andReturn(true);
        });

        $mock->stubVueDataPlaceholders = [$search];
        $mock->replaceVueData($stub);

        $this->assertIsString($stub, 'The type should be a string.');
        $this->assertNotEmpty($stub, 'The stub value shouldn\'t be empty.');
        $this->assertSame($expectedStub, $stub, 'The stub should contain the updated string.');
        $this->assertNotSame($originalStub, $stub, 'The stub should have been updated within this test.');
    }

    /**
     * A test to get Vue data.
     *
     * @return void
     */
    public function test_get_vue_data()
    {
        $inputs = $this->getMockColumns();

        foreach ($inputs as $input) {
            $result = $this->getVueDataString($input);
            $expectedResult = $input['name'] . ": null,\n\t\t\t";
            
            $this->assertIsString($result, 'The type should be a string.');
            $this->assertNotEmpty($result, 'The table name value should be empty.');
            $this->assertSame($expectedResult, $result, 'The stub should contain the updated string.');
        }
    }

    /**
     * A test to replace Vue post data.
     *
     * @return void
     */
    public function test_replace_vue_post_data()
    {
        $inputs = $this->getMockColumns();
        $search = 'search';
        $baseStub = '-in-stub';
        $stub = $originalStub = $search . $baseStub;
        $expectedStub = str_repeat($search, count($inputs)) . $baseStub;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($search, $inputs) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('argument')
                ->with('inputs')
                ->once()
                ->andReturn($inputs);

            foreach ($inputs as $input) {
                $mock->shouldReceive('getVuePostDataString')
                    ->with($input)
                    ->andReturn($search);
            }

            $mock->shouldReceive('needsVueFrontend')
                ->once()
                ->andReturn(true);
        });

        $mock->stubVuePostDataPlaceholders = [$search];
        $mock->replaceVuePostData($stub);

        $this->assertIsString($stub, 'The type should be a string.');
        $this->assertNotEmpty($stub, 'The stub value shouldn\'t be empty.');
        $this->assertSame($expectedStub, $stub, 'The stub should contain the updated string.');
        $this->assertNotSame($originalStub, $stub, 'The stub should have been updated within this test.');
    }

    /**
     * A test to replace form cancel URL variables.
     *
     * @return void
     */
    public function test_replace_form_cancel_url()
    {
        $inputs = $this->getMockColumns();
        $name = 'name';
        $search = 'search';
        $baseStub = '-in-stub';
        $stub = $originalStub = $search . $baseStub;
        $expectedStub = '/' . $name . $baseStub;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($search, $inputs, $name) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('argument')
                ->with('name')
                ->once()
                ->andReturn($name);
        });

        $mock->stubCancelUrlPlaceholders = [$search];
        $mock->replaceFormCancelUrl($stub);

        $this->assertIsString($stub, 'The type should be a string.');
        $this->assertNotEmpty($stub, 'The stub value shouldn\'t be empty.');
        $this->assertSame($expectedStub, $stub, 'The stub should contain the updated string.');
        $this->assertNotSame($originalStub, $stub, 'The stub should have been updated within this test.');
    }

    /**
     * A test to replace replace vue component name.
     *
     * @return void
     */
    public function test_replace_vue_component_name()
    {
        $type = 'edit';
        $tableName = 'table_names';
        $search = 'search';
        $baseStub = '-in-stub';
        $stub = $originalStub = $search . $baseStub;
        $expectedStub = 'TableName' . ucfirst($type) . $baseStub;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($tableName, $type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('needsVueFrontend')
                ->once()
                ->andReturn(true);

            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);

            $mock->shouldReceive('getTableName')
                ->once()
                ->andReturn($tableName);
        });

        $mock->stubVueComponentPlaceholders = [$search];
        $mock->replaceVueComponentName($stub);

        $this->assertIsString($stub, 'The type should be a string.');
        $this->assertNotEmpty($stub, 'The stub value shouldn\'t be empty.');
        $this->assertSame($expectedStub, $stub, 'The stub should contain the updated string.');
        $this->assertNotSame($originalStub, $stub, 'The stub should have been updated within this test.');
    }
}