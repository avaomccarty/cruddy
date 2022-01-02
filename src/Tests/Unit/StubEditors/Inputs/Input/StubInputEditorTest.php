<?php

namespace Cruddy\Tests\Unit\StubEditors\Inputs\Input;

use Cruddy\StubEditors\Inputs\Input\ViewStubInputEditor;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;

class StubInputEditorTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
        $this->inputDefaults = [
            'string' => 'text',
            'integer' => 'number',
            'bigInteger' => 'number',
            'submit' => 'submit',
        ];
    }
    
    /**
     * A test to get the text stub file for default frontend.
     *
     * @return void
     */
    public function test_get_text_stub_file_for_default_frontend()
    {
        $inputs = array_filter($this->inputDefaults, function ($inputType) {
            return $inputType === 'text';
        });

        foreach ($inputs as $columnType => $inputType) {
            $column = new ColumnDefinition([
                'type'  => $columnType
            ]);

            $expectedResult = '<input type="text" name="{{ name }}" {{ data }} class="input my-2" placeholder="{{ name }}">' . "\n";

            Config::shouldReceive('get')
                ->with('cruddy.stubs_folder')
                ->andReturn('stubs');
            Config::shouldReceive('get')
                ->with('cruddy.frontend_scaffolding')
                ->andReturn('default');
            Config::shouldReceive('get')
                ->with('cruddy.input_defaults')
                ->andReturn($this->inputDefaults);

            Config::partialMock();

            $result = (new ViewStubInputEditor($column))
                ->getStubFile();

            $this->assertSame($expectedResult, $result);
        }

        $this->assertTrue(count($inputs) > 0, 'There should be at least one input of this type.');
    }

    /**
     * A test to get the integer stub file for the default frontend.
     *
     * @return void
     */
    public function test_get_stub_integer_file_for_default_frontend()
    {
        $inputs = array_filter($this->inputDefaults, function ($inputType) {
            return $inputType === 'number';
        });

        foreach ($inputs as $columnType => $inputType) {
            $column = new ColumnDefinition([
                'type'  => $columnType
            ]);

            $expectedResult = '<input type="number" name="{{ name }}" {{ data }} class="input my-2">' . "\n";

            Config::shouldReceive('get')
                ->with('cruddy.stubs_folder')
                ->andReturn('stubs');
            Config::shouldReceive('get')
                ->with('cruddy.frontend_scaffolding')
                ->andReturn('default');
            Config::shouldReceive('get')
                ->with('cruddy.input_defaults')
                ->andReturn($this->inputDefaults);

            Config::partialMock();

            $result = (new ViewStubInputEditor($column))
                ->getStubFile();

            $this->assertSame($expectedResult, $result);
        }

        $this->assertTrue(count($inputs) > 0, 'There should be at least one input of this type.');
    }

    /**
     * A test to get the submit stub file for the default frontend.
     *
     * @return void
     */
    public function test_get_stub_submit_file_for_default_frontend()
    {
        $inputs = array_filter($this->inputDefaults, function ($inputType) {
            return $inputType === 'submit';
        });

        foreach ($inputs as $columnType => $inputType) {
            $column = new ColumnDefinition([
                'type'  => $columnType
            ]);

            $expectedResult = '<input type="submit" value="{{ value }}" class="button is-primary my-2">' . "\n";

            Config::shouldReceive('get')
                ->with('cruddy.stubs_folder')
                ->andReturn('stubs');
            Config::shouldReceive('get')
                ->with('cruddy.frontend_scaffolding')
                ->andReturn('default');
            Config::shouldReceive('get')
                ->with('cruddy.input_defaults')
                ->andReturn($this->inputDefaults);

            Config::partialMock();

            $result = (new ViewStubInputEditor($column))
                ->getStubFile();

            $this->assertSame($expectedResult, $result);
        }

        $this->assertTrue(count($inputs) > 0, 'There should be at least one input of this type.');
    }
    
    /**
     * A test to get the text stub file for vue frontend.
     *
     * @return void
     */
    public function test_get_text_stub_file_for_vue_frontend()
    {
        $inputs = array_filter($this->inputDefaults, function ($inputType) {
            return $inputType === 'text';
        });

        foreach ($inputs as $columnType => $inputType) {
            $column = new ColumnDefinition([
                'type'  => $columnType
            ]);

            $expectedResult = '<input type="text" name="{{ name }}" {{ data }} class="input my-2" placeholder="{{ name }}" v-model="{{ vModelName }}">' . "\n";

            Config::shouldReceive('get')
                ->with('cruddy.stubs_folder')
                ->andReturn('stubs');
            Config::shouldReceive('get')
                ->with('cruddy.frontend_scaffolding')
                ->andReturn('vue');
            Config::shouldReceive('get')
                ->with('cruddy.input_defaults')
                ->andReturn($this->inputDefaults);

            Config::partialMock();

            $result = (new ViewStubInputEditor($column))
                ->getStubFile();

            $this->assertSame($expectedResult, $result);
        }

        $this->assertTrue(count($inputs) > 0, 'There should be at least one input of this type.');
    }

    /**
     * A test to get the integer stub file for the vue frontend.
     *
     * @return void
     */
    public function test_get_stub_integer_file_for_vue_frontend()
    {
        $inputs = array_filter($this->inputDefaults, function ($inputType) {
            return $inputType === 'number';
        });

        foreach ($inputs as $columnType => $inputType) {
            $column = new ColumnDefinition([
                'type'  => $columnType
            ]);

            $expectedResult = '<input type="number" name="{{ name }}" {{ data }} class="input my-2" v-model="{{ vModelName }}">' . "\n";

            Config::shouldReceive('get')
                ->with('cruddy.stubs_folder')
                ->andReturn('stubs');
            Config::shouldReceive('get')
                ->with('cruddy.frontend_scaffolding')
                ->andReturn('vue');
            Config::shouldReceive('get')
                ->with('cruddy.input_defaults')
                ->andReturn($this->inputDefaults);

            Config::partialMock();

            $result = (new ViewStubInputEditor($column))
                ->getStubFile();

            $this->assertSame($expectedResult, $result);
        }

        $this->assertTrue(count($inputs) > 0, 'There should be at least one input of this type.');
    }

    /**
     * A test to get the submit stub file for the vue frontend.
     *
     * @return void
     */
    public function test_get_stub_submit_file_for_vue_frontend()
    {
        $inputs = array_filter($this->inputDefaults, function ($inputType) {
            return $inputType === 'submit';
        });

        foreach ($inputs as $columnType => $inputType) {
            $column = new ColumnDefinition([
                'type'  => $columnType
            ]);

            $expectedResult = '<input type="submit" class="button is-primary my-2">' . "\n";

            Config::shouldReceive('get')
                ->with('cruddy.stubs_folder')
                ->andReturn('stubs');
            Config::shouldReceive('get')
                ->with('cruddy.frontend_scaffolding')
                ->andReturn('vue');
            Config::shouldReceive('get')
                ->with('cruddy.input_defaults')
                ->andReturn($this->inputDefaults);

            Config::partialMock();

            $result = (new ViewStubInputEditor($column))
                ->getStubFile();

            $this->assertSame($expectedResult, $result);
        }

        $this->assertTrue(count($inputs) > 0, 'There should be at least one input of this type.');
    }
}