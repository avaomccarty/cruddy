<?php

namespace Cruddy\Tests\Unit\Traits\Stubs;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\ConfigTrait;
use Cruddy\Traits\Stubs\FormTrait;
use Cruddy\Traits\Stubs\StubTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class FormTraitTest extends TestCase
{
    use CommandTrait, ConfigTrait, FormTrait, StubTrait, TestTrait;

    /**
     * A test to replace the form action for a create file or an index file that needs a Vue frontend.
     *
     * @return void
     */
    public function test_replace_form_action_for_create_file_type_or_index_file_type_with_vue_frontend()
    {
        $types = [
            'create',
            'index',
        ];
        $name = 'name';
        $search = 'search';
        $baseStub = '-in-stub';
        $stub = $originalStub = $search . $baseStub;
        $expectedStub = '/' . $name . $baseStub;

        foreach ($types as $type) {
            $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($name, $type) {
                $mock->shouldAllowMockingProtectedMethods();
                $mock->shouldReceive('argument')
                    ->with('name')
                    ->andReturn($name);

                $mock->shouldReceive('getType')
                    ->andReturn($type);

                $mock->shouldReceive('needsVueFrontend')
                    ->andReturn(true);
            });

            $mock->actionPlaceholders = [$search];
            $mock->replaceFormAction($stub);

            $this->assertIsString($stub, 'The type should be a string.');
            $this->assertNotEmpty($stub, 'The stub value shouldn\'t be empty.');
            $this->assertSame($expectedStub, $stub, 'The stub should contain the updated string.');
            $this->assertNotSame($originalStub, $stub, 'The stub should have been updated within this test.');
        }
    }

    /**
     * A test to replace the form action for an edit file that doesn't need a Vue frontend.
     *
     * @return void
     */
    public function test_replace_form_action_for_edit_file_type_without_vue_frontend()
    {
        $name = 'name';
        $type = 'edit';
        $search = 'search';
        $baseStub = '-in-stub';
        $stub = $originalStub = $search . $baseStub;
        $expectedStub = '/' . $name . '/{{ $' . $name . '->id }}' . $baseStub;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($name, $type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('argument')
                ->with('name')
                ->andReturn($name);

            $mock->shouldReceive('getType')
                ->andReturn($type);

            $mock->shouldReceive('needsVueFrontend')
                ->andReturn(false);
        });

        $mock->actionPlaceholders = [$search];
        $mock->replaceFormAction($stub);

        $this->assertIsString($stub, 'The type should be a string.');
        $this->assertNotEmpty($stub, 'The stub value shouldn\'t be empty.');
        $this->assertSame($expectedStub, $stub, 'The stub should contain the updated string.');
        $this->assertNotSame($originalStub, $stub, 'The stub should have been updated within this test.');
    }

    /**
     * A test to replace the form action for an edit file that needs a Vue frontend.
     *
     * @return void
     */
    public function test_replace_form_action_for_edit_file_type_with_vue_frontend()
    {
        $name = 'name';
        $type = 'edit';
        $search = 'search';
        $baseStub = '-in-stub';
        $stub = $originalStub = $search . $baseStub;
        $expectedStub = "'/$name/' + this.item.id" . $baseStub;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($name, $type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('argument')
                ->with('name')
                ->andReturn($name);

            $mock->shouldReceive('getType')
                ->andReturn($type);

            $mock->shouldReceive('needsVueFrontend')
                ->andReturn(true);
        });

        $mock->actionPlaceholders = [$search];
        $mock->replaceFormAction($stub);

        $this->assertIsString($stub, 'The type should be a string.');
        $this->assertNotEmpty($stub, 'The stub value shouldn\'t be empty.');
        $this->assertSame($expectedStub, $stub, 'The stub should contain the updated string.');
        $this->assertNotSame($originalStub, $stub, 'The stub should have been updated within this test.');
    }
}