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
     * A test to get Vue data.
     *
     * @return void
     */
    public function test_get_vue_data()
    {
        $inputs = $this->getMockColumns();

        foreach ($inputs as $input) {
            $result = $this->getVueDataString($input);
            $expectedResult = $input['name'] . ": null," . $this->endOfDataLine;
            
            $this->assertIsString($result, 'The type should be a string.');
            $this->assertNotEmpty($result, 'The table name value should be empty.');
            $this->assertSame($expectedResult, $result, 'The stub should contain the updated string.');
        }
    }

    /**
     * A test for getting the vue post data string for non-edit types.
     *
     * @return void
     */
    public function test_get_vue_post_data_string_for_not_edit_types()
    {
        $input = $this->getMockColumns()[0];
        $expectedResult = "name-string: this.name-string," . $this->endOfPostDataLine;

        $result = $this->getVuePostDataString($input);

        $this->assertSame($expectedResult, $result, 'The Vue post data string was incorrect.');
    }

    /**
     * A test for getting the vue post data string for edit types.
     *
     * @return void
     */
    public function test_get_vue_post_data_string_for_edit_type()
    {
        $type = 'edit';
        $input = $this->getMockColumns()[0];
        $expectedResult = "name-string: this.item.name-string," . $this->endOfPostDataLine;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
        });
        

        $result = $mock->getVuePostDataString($input);

        $this->assertSame($expectedResult, $result, 'The Vue post data string was incorrect.');
    }

    /**
     * A test for getting the component name.
     *
     * @return void
     */
    public function test_get_component_name()
    {
        $name = 'nameForTest';
        $type = 'type';
        $expectedResult = 'name-for-test-' . $type;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
        });
        
        $result = $mock->getComponentName($name);

        $this->assertIsString($result);
        $this->assertSame($expectedResult, $result);
    }
}