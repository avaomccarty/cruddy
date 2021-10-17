<?php

namespace Cruddy\Tests\Unit;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\ViewMakeCommandTrait;
use Orchestra\Testbench\TestCase;

class ViewMakeCommandTraitTest extends TestCase
{
    use ViewMakeCommandTrait, TestTrait;

    /**
     * A test to get the default namespace.
     *
     * @return void
     */
    public function test_get_default_namespace()
    {
        $rootNamespace = 'rootNamespace';
        $expectedResult = $rootNamespace . '\resources\views';
        $result = $this->getDefaultNamespace($rootNamespace);

        $this->assertIsString($result, 'The type should be a string.');
        $this->assertNotEmpty($result, 'The table name value should be empty.');
        $this->assertSame($expectedResult, $result, 'The default namespace is incorrect.');
    }
}