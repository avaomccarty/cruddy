<?php

namespace Cruddy\Tests\Unit\Traits\Stubs;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\Stubs\ModelTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class ModelTraitTest extends TestCase
{
    use CommandTrait, ModelTrait, TestTrait;

    /**
     * A test getting the class basename.
     *
     * @return void
     */
    public function test_get_class_basename()
    {
        $className = 'Class';
        $model = 'Foo\Bar\Baz\\' . $className;

        $result = $this->getClassBasename($model);

        $this->assertSame(strtolower($className), $result);
    }

    /**
     * A test to replace the model placeholder.
     *
     * @return void
     */
    public function test_replace_model_placeholder()
    {
        $className = 'Class';
        $model = 'Foo\Bar\Baz\\' . $className;
        $originalStub = $expectedStub = $stub = 'stub-';

        foreach ($this->stubModelPlaceholders as $placeholder) {
            $stub .= $placeholder;
            $expectedStub .= $className;
        }

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($model, $className) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getClassBasename')
                ->with($model)
                ->once()
                ->andReturn($className);
        });
        

        $result = $mock->replaceModelPlaceholders($model, $stub);

        $this->assertIsObject($result);
        $this->assertInstanceOf(self::class, $result);
        $this->assertNotSame($originalStub, $stub, 'The stub should have been updated.');
        $this->assertSame($expectedStub, $stub, 'The stub did not replace the model variables correctly.');
    }

    /**
     * A test to replace the model variable placeholder.
     *
     * @return void
     */
    public function test_replace_model_variable_placeholder()
    {
        $className = 'Class';
        $model = 'Foo\Bar\Baz\\' . $className;
        $originalStub = $expectedStub = $stub = 'stub-';

        foreach ($this->stubModelVariablePlaceholders as $placeholder) {
            $stub .= $placeholder;
            $expectedStub .= $className;
        }

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($model, $className) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getClassBasename')
                ->with($model)
                ->once()
                ->andReturn($className);
        });
        

        $result = $mock->replaceModelVariablePlaceholders($model, $stub);

        $this->assertIsObject($result);
        $this->assertInstanceOf(self::class, $result);
        $this->assertNotSame($originalStub, $stub, 'The stub should have been updated.');
        $this->assertSame($expectedStub, $stub, 'The stub did not replace the model variables correctly.');
    }

    /**
     * A test to replace the full model placeholder.
     *
     * @return void
     */
    public function test_replace_full_model_placeholder()
    {
        $model = 'Foo\Bar\Baz\\';
        $originalStub = $expectedStub = $stub = 'stub-';

        foreach ($this->stubFullModelClassPlaceholders as $placeholder) {
            $stub .= $placeholder;
            $expectedStub .= $model;
        }

        $result = $this->replaceFullModelPlaceholders($model, $stub);

        $this->assertIsObject($result);
        $this->assertInstanceOf(self::class, $result);
        $this->assertNotSame($originalStub, $stub, 'The stub should have been updated.');
        $this->assertSame($expectedStub, $stub, 'The stub did not replace the model variables correctly.');
    }
}