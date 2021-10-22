<?php

namespace Cruddy\Tests\Unit\Traits;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\VueCommandTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class VueCommandTraitTest extends TestCase
{
    use VueCommandTrait, TestTrait;

    /**
     * A test to get the Vue component.
     *
     * @return void
     */
    public function test_get_studly_vue_component_name()
    {
        $tableName = 'tableName';
        $studlySingular = 'studlySingular';
        $type = 'type';
        $expectedResult = $studlySingular . ucfirst($type);

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($tableName, $studlySingular, $type) {
            $mock->shouldAllowMockingProtectedMethods();
                $mock->shouldReceive('getTableName')
                    ->once()
                    ->andReturn($tableName);
                $mock->shouldReceive('getStudlySingular')
                    ->with($tableName)
                    ->once()
                    ->andReturn($studlySingular);
                $mock->shouldReceive('getType')
                    ->once()
                    ->andReturn($type);
        });

        $result = $mock->getStudlyComponentName();

        $this->assertSame($expectedResult, $result);
    }
}