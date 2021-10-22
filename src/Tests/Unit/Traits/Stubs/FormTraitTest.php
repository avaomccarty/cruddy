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

    /**
     * A test for gettting the cancel URL.
     *
     * @return void
     */
    public function test_get_cancel_url()
    {
        $name = 'name';
        $expectedResult = '/' . $name;

        $result = $this->getCancelUrl($name);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test for if the action should return to the index when the type is index and the frontend is Vue.
     *
     * @return void
     */
    public function test_should_return_to_index_for_index_type_with_needs_vue()
    {
        $type = 'index';
        $needsVue = true;
        
        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type, $needsVue) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
            $mock->shouldReceive('needsVueFrontend')
                ->once()
                ->andReturn($needsVue);
        });
        
        $result = $mock->shouldSendToIndex();

        $this->assertTrue($result);
    }

    /**
     * A test for if the action should return to the index when the type is index and the frontend is not Vue.
     *
     * @return void
     */
    public function test_should_return_to_index_for_index_type_without_vue()
    {
        $type = 'index';
        $needsVue = false;
        
        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type, $needsVue) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
            $mock->shouldReceive('needsVueFrontend')
                ->once()
                ->andReturn($needsVue);
        });
        
        $result = $mock->shouldSendToIndex();

        $this->assertFalse($result);
    }

    /**
     * A test for if the action should return to the index when the type is create
     *
     * @return void
     */
    public function test_should_return_to_index_for_create_type()
    {
        $type = 'create';
        
        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
        });
        
        $result = $mock->shouldSendToIndex();

        $this->assertTrue($result);
    }

    /**
     * A test for if the action should return to the index when the type is not create nor index.
     *
     * @return void
     */
    public function test_should_return_to_index_for_non_create_and_non_index_types()
    {
        $type = 'edit';
        
        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
        });
        
        $result = $mock->shouldSendToIndex();

        $this->assertFalse($result);
    }

    /**
     * A test for getting the route for Vue frontend and edit type.
     *
     * @return void
     */
    public function test_get_route_for_edit_type_with_vue_frontend()
    {
        $type = 'edit';
        $name = 'name';
        $expectedResult = "'/$name/' + this.item.id";

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
            $mock->shouldReceive('shouldSendToIndex')
                ->once()
                ->andReturn(false);
            $mock->shouldReceive('needsVueFrontend')
                ->once()
                ->andReturn(true);
        });

        $result = $mock->getActionRoute($name);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test for getting the route for non-Vue frontend and edit type.
     *
     * @return void
     */
    public function test_get_route_for_edit_type_with_non_vue_frontend()
    {
        $type = 'edit';
        $name = 'name';
        $camelCaseSingular = 'camelCaseSingular';
        $expectedResult = '/' . $name . '/{{ $' . $camelCaseSingular . '->id }}';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type, $name, $camelCaseSingular) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
            $mock->shouldReceive('shouldSendToIndex')
                ->once()
                ->andReturn(false);
            $mock->shouldReceive('getCamelCaseSingular')
                ->with($name)
                ->once()
                ->andReturn($camelCaseSingular);
            $mock->shouldReceive('needsVueFrontend')
                ->once()
                ->andReturn(false);
        });

        $result = $mock->getActionRoute($name);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test for getting the route to the index.
     *
     * @return void
     */
    public function test_get_route_for_when_should_send_to_index()
    {
        $name = 'name';
        $expectedResult = '/' . $name;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('shouldSendToIndex')
                ->once()
                ->andReturn(true);
            $mock->shouldReceive('getType')
                ->never();
        });

        $result = $mock->getActionRoute($name);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the edit action route with Vue frontend.
     *
     * @return void
     */
    public function test_get_edit_action_route_with_vue_frontend()
    {
        $needsVueFrontend = true;
        $name = 'name';
        $expectedResult = "'/$name/' + this.item.id";

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($needsVueFrontend) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('needsVueFrontend')
                ->once()
                ->andReturn($needsVueFrontend);
            $mock->shouldReceive('getCamelCaseSingular')
                ->never();
        });

        $result = $mock->getEditActionRoute($name);
        
        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the edit action route without Vue frontend.
     *
     * @return void
     */
    public function test_get_edit_action_route_without_vue_frontend()
    {
        $needsVueFrontend = false;
        $name = 'name';
        $camelCaseSingular = 'camelCaseSingular';
        $expectedResult = '/' . $name . '/{{ $' . $camelCaseSingular . '->id }}';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($needsVueFrontend, $name, $camelCaseSingular) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('needsVueFrontend')
                ->once()
                ->andReturn($needsVueFrontend);
            $mock->shouldReceive('getCamelCaseSingular')
                ->with($name)
                ->once()
                ->andReturn($camelCaseSingular);
        });

        $result = $mock->getEditActionRoute($name);
        
        $this->assertSame($expectedResult, $result);
    }
}