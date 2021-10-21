<?php

namespace Cruddy\Tests\Unit\Traits\Stubs;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\Stubs\ResourceTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class ResourceTraitTest extends TestCase
{
    use CommandTrait, ResourceTrait, TestTrait;

    /**
     * A test to replace the model variable placeholder.
     *
     * @return void
     */
    public function test_replace_model_variable_placeholder()
    {
        $originalStub = $expectedStub = $stub = 'stub-';
        $resource = 'resource-';

        foreach ($this->resourcePlaceholders as $placeholder) {
            $stub .= $placeholder;
            $expectedStub .= $resource;
        }

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($resource) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getResource')
                ->once()
                ->andReturn($resource);
        });
        

        $result = $mock->replaceResource($stub);

        $this->assertIsObject($result);
        $this->assertInstanceOf(self::class, $result);
        $this->assertNotSame($originalStub, $stub, 'The stub should have been updated.');
        $this->assertSame($expectedStub, $stub, 'The stub did not replace the model variables correctly.');
    }
}