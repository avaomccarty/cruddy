<?php

namespace Cruddy\Tests\Unit\Traits;

use Cruddy\Tests\TestTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class ViewMakeCommandTraitTest extends TestCase
{
    // use TestTrait;

    // /**
    //  * A test to get the default namespace.
    //  *
    //  * @return void
    //  */
    // public function test_get_default_namespace()
    // {
    //     $rootNamespace = 'rootNamespace';
    //     $expectedResult = $rootNamespace . '\resources\views';
    //     $result = $this->getDefaultNamespace($rootNamespace);

    //     $this->assertIsString($result, 'The type should be a string.');
    //     $this->assertNotEmpty($result, 'The table name value should be empty.');
    //     $this->assertSame($expectedResult, $result, 'The default namespace is incorrect.');
    // }
        
    // /**
    //  * A test to get Vue data.
    //  *
    //  * @return void
    //  */
    // public function test_get_vue_data()
    // {
    //     $inputs = $this->getMockColumns();

    //     foreach ($inputs as $input) {
    //         $result = $this->getVueDataString($input);
    //         $expectedResult = $input['name'] . ": null," . $this->endOfDataLine;
            
    //         $this->assertIsString($result, 'The type should be a string.');
    //         $this->assertNotEmpty($result, 'The table name value should be empty.');
    //         $this->assertSame($expectedResult, $result, 'The stub should contain the updated string.');
    //     }
    // }

    // /**
    //  * A test for getting the Vue post data string for non-edit types.
    //  *
    //  * @return void
    //  */
    // public function test_get_vue_post_data_string_for_not_edit_types()
    // {
    //     $input = $this->getMockColumns()[0];
    //     $expectedResult = "name-string: this.name-string," . $this->endOfPostDataLine;

    //     $result = $this->getVuePostDataString($input);

    //     $this->assertSame($expectedResult, $result, 'The Vue post data string was incorrect.');
    // }

    // /**
    //  * A test for getting the Vue post data string for edit types.
    //  *
    //  * @return void
    //  */
    // public function test_get_vue_post_data_string_for_edit_type()
    // {
    //     $type = 'edit';
    //     $input = $this->getMockColumns()[0];
    //     $expectedResult = "name-string: this.item.name-string," . $this->endOfPostDataLine;

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('getType')
    //             ->once()
    //             ->andReturn($type);
    //     });
        

    //     $result = $mock->getVuePostDataString($input);

    //     $this->assertSame($expectedResult, $result, 'The Vue post data string was incorrect.');
    // }

    // /**
    //  * A test to replace the Vue data.
    //  *
    //  * @return void
    //  */
    // public function test_replace_vue_data()
    // {
    //     $inputs = $this->getMockColumns();
    //     $originalVueDataString = $expectedVueDataString = '';
    //     $originalVuePostDataString = $expectedVuePostDataString = '';

    //     foreach ($inputs as $input) {
    //         $expectedVueDataString .= $input->name;
    //         $expectedVuePostDataString .= $input->name;
    //     }

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($inputs) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         foreach ($inputs as $input) {
    //             $mock->shouldReceive('getVueDataString')
    //                 ->with($input)
    //                 ->once()
    //                 ->andReturn($input->name);
    //             $mock->shouldReceive('getVuePostDataString')
    //                 ->with($input)
    //                 ->once()
    //                 ->andReturn($input->name);
    //         }
    //     });
        
    //     $mock->replaceVueData($inputs, $originalVueDataString, $originalVuePostDataString);

    //     $this->assertSame($expectedVueDataString, $originalVueDataString);
    //     $this->assertSame($expectedVuePostDataString, $originalVuePostDataString);
    // }

    //     /**
    //  * A test for if the action should return to the index when the type is index and the frontend is Vue.
    //  *
    //  * @return void
    //  */
    // public function test_should_return_to_index_for_index_type_with_needs_vue()
    // {
    //     $type = 'index';
    //     $needsVue = true;
        
    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type, $needsVue) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('getType')
    //             ->once()
    //             ->andReturn($type);
    //         $mock->shouldReceive('needsVueFrontend')
    //             ->once()
    //             ->andReturn($needsVue);
    //     });
        
    //     $result = $mock->shouldSendToIndex();

    //     $this->assertTrue($result);
    // }

    // /**
    //  * A test for if the action should return to the index when the type is index and the frontend is not Vue.
    //  *
    //  * @return void
    //  */
    // public function test_should_return_to_index_for_index_type_without_vue()
    // {
    //     $type = 'index';
    //     $needsVue = false;
        
    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type, $needsVue) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('getType')
    //             ->once()
    //             ->andReturn($type);
    //         $mock->shouldReceive('needsVueFrontend')
    //             ->once()
    //             ->andReturn($needsVue);
    //     });
        
    //     $result = $mock->shouldSendToIndex();

    //     $this->assertFalse($result);
    // }

    // /**
    //  * A test for if the action should return to the index when the type is create
    //  *
    //  * @return void
    //  */
    // public function test_should_return_to_index_for_create_type()
    // {
    //     $type = 'create';
        
    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('getType')
    //             ->once()
    //             ->andReturn($type);
    //     });
        
    //     $result = $mock->shouldSendToIndex();

    //     $this->assertTrue($result);
    // }

    // /**
    //  * A test for if the action should return to the index when the type is not create nor index.
    //  *
    //  * @return void
    //  */
    // public function test_should_return_to_index_for_non_create_and_non_index_types()
    // {
    //     $type = 'edit';
        
    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('getType')
    //             ->once()
    //             ->andReturn($type);
    //     });
        
    //     $result = $mock->shouldSendToIndex();

    //     $this->assertFalse($result);
    // }

    // /**
    //  * A test for getting the route for Vue frontend and edit type.
    //  *
    //  * @return void
    //  */
    // public function test_get_route_for_edit_type_with_vue_frontend()
    // {
    //     $type = 'edit';
    //     $name = 'name';
    //     $expectedResult = "'/$name/' + this.item.id";

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('getType')
    //             ->once()
    //             ->andReturn($type);
    //         $mock->shouldReceive('shouldSendToIndex')
    //             ->once()
    //             ->andReturn(false);
    //         $mock->shouldReceive('needsVueFrontend')
    //             ->once()
    //             ->andReturn(true);
    //     });

    //     $result = $mock->getActionRoute($name);

    //     $this->assertSame($expectedResult, $result);
    // }

    // /**
    //  * A test for getting the route for non-Vue frontend and edit type.
    //  *
    //  * @return void
    //  */
    // public function test_get_route_for_edit_type_with_non_vue_frontend()
    // {
    //     $type = 'edit';
    //     $name = 'name';
    //     $camelCaseSingular = 'camelCaseSingular';
    //     $expectedResult = '/' . $name . '/{{ $' . $camelCaseSingular . '->id }}';

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type, $name, $camelCaseSingular) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('getType')
    //             ->once()
    //             ->andReturn($type);
    //         $mock->shouldReceive('shouldSendToIndex')
    //             ->once()
    //             ->andReturn(false);
    //         $mock->shouldReceive('getCamelCaseSingular')
    //             ->with($name)
    //             ->once()
    //             ->andReturn($camelCaseSingular);
    //         $mock->shouldReceive('needsVueFrontend')
    //             ->once()
    //             ->andReturn(false);
    //     });

    //     $result = $mock->getActionRoute($name);

    //     $this->assertSame($expectedResult, $result);
    // }

    // /**
    //  * A test for getting the route to the index.
    //  *
    //  * @return void
    //  */
    // public function test_get_route_for_when_should_send_to_index()
    // {
    //     $name = 'name';
    //     $expectedResult = '/' . $name;

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('shouldSendToIndex')
    //             ->once()
    //             ->andReturn(true);
    //         $mock->shouldReceive('getType')
    //             ->never();
    //     });

    //     $result = $mock->getActionRoute($name);

    //     $this->assertSame($expectedResult, $result);
    // }

    // /**
    //  * A test to get the edit action route with Vue frontend.
    //  *
    //  * @return void
    //  */
    // public function test_get_edit_action_route_with_vue_frontend()
    // {
    //     $needsVueFrontend = true;
    //     $name = 'name';
    //     $expectedResult = "'/$name/' + this.item.id";

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($needsVueFrontend) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('needsVueFrontend')
    //             ->once()
    //             ->andReturn($needsVueFrontend);
    //         $mock->shouldReceive('getCamelCaseSingular')
    //             ->never();
    //     });

    //     $result = $mock->getEditActionRoute($name);
        
    //     $this->assertSame($expectedResult, $result);
    // }

    // /**
    //  * A test to get the edit action route without Vue frontend.
    //  *
    //  * @return void
    //  */
    // public function test_get_edit_action_route_without_vue_frontend()
    // {
    //     $needsVueFrontend = false;
    //     $name = 'name';
    //     $camelCaseSingular = 'camelCaseSingular';
    //     $expectedResult = '/' . $name . '/{{ $' . $camelCaseSingular . '->id }}';

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($needsVueFrontend, $name, $camelCaseSingular) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('needsVueFrontend')
    //             ->once()
    //             ->andReturn($needsVueFrontend);
    //         $mock->shouldReceive('getCamelCaseSingular')
    //             ->with($name)
    //             ->once()
    //             ->andReturn($camelCaseSingular);
    //     });

    //     $result = $mock->getEditActionRoute($name);
        
    //     $this->assertSame($expectedResult, $result);
    // }

    // /**
    //  * A test to get the edit URL for any non-Vue frontend.
    //  *
    //  * @return void
    //  */
    // public function test_get_edit_url_for_non_vue_frontend()
    // {
    //     $name = 'name';
    //     $camelCaseSingular = 'camelCaseSingular';
    //     $expectedResult = '/' . $name . '/{{ $' . $camelCaseSingular . '->id }}/edit';

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($name, $camelCaseSingular) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('needsVueFrontend')
    //             ->once()
    //             ->andReturn(false);
    //         $mock->shouldReceive('getCamelCaseSingular')
    //             ->with($name)
    //             ->once()
    //             ->andReturn($camelCaseSingular);
    //     });

    //     $result = $mock->getEditUrl($name);

    //     $this->assertSame($expectedResult, $result);
    // }

    // /**
    //  * A test to get the edit URL for any Vue frontend.
    //  *
    //  * @return void
    //  */
    // public function test_get_edit_url_for_vue_frontend()
    // {
    //     $name = 'name';
    //     $expectedResult = "'/$name/' + item.id + '/edit'";

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('needsVueFrontend')
    //             ->once()
    //             ->andReturn(true);
    //         $mock->shouldReceive('getCamelCaseSingular')
    //             ->never();
    //     });

    //     $result = $mock->getEditUrl($name);

    //     $this->assertSame($expectedResult, $result);
    // }

    // /**
    //  * A test to get the Vue component.
    //  *
    //  * @return void
    //  */
    // public function test_get_studly_vue_component_name()
    // {
    //     $tableName = 'tableName';
    //     $studlySingular = 'studlySingular';
    //     $type = 'type';
    //     $expectedResult = $studlySingular . ucfirst($type);

    //     $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($tableName, $studlySingular, $type) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //             $mock->shouldReceive('getTableName')
    //                 ->once()
    //                 ->andReturn($tableName);
    //             $mock->shouldReceive('getStudlySingular')
    //                 ->with($tableName)
    //                 ->once()
    //                 ->andReturn($studlySingular);
    //             $mock->shouldReceive('getType')
    //                 ->once()
    //                 ->andReturn($type);
    //     });

    //     $result = $mock->getStudlyComponentName();

    //     $this->assertSame($expectedResult, $result);
    // }
}