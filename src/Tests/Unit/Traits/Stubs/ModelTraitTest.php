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
}