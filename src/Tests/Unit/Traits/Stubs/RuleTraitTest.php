<?php

namespace Cruddy\Tests\Unit\Traits\Stubs;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\Stubs\RuleTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;
use stdClass;

class RuleTraitTest extends TestCase
{
    use CommandTrait, RuleTrait, TestTrait;

    /**
     * A test to replace the rules within a stub.
     *
     * @return void
     */
    public function test_replace_rules()
    {
        $rules = ['rules'];
        $stub = 'stub';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($rules, $stub) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getNonIdRules')
                ->once()
                ->andReturn($rules);
            $mock->shouldReceive('updateStubWithRules')
                ->with($stub, $rules)
                ->once();
        });

        $result = $mock->replaceRules($stub);
        
        $this->assertIsObject($result);
        $this->assertInstanceOf(self::class, $result);
    }

    /**
     * A test getting the non-id rules within the rules.
     *
     * @return void
     */
    public function test_get_non_id_rules()
    {
        $rule1 = new stdClass();
        $rule1->name = 'name-1';

        $rule2 = new stdClass();
        $rule2->name = 'name-2';

        $ruleId = new stdClass();
        $ruleId->name = 'id';

        $rules = [
            $rule1,
            $ruleId,
            $rule2,
        ];

        $expectedResult= [
            $rule1,
            $rule2,
        ];

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($rules) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getRules')
                ->once()
                ->andReturn($rules);
        });
        

        $result = $mock->getNonIdRules($rules);

        $this->assertSame($expectedResult, array_values($result), 'The rules array did not filter correctly to only include non-id columns.');
    }

    /**
     * A test to get the rules.
     *
     * @return void
     */
    public function test_get_rules()
    {
        $expectedResult = $rules = ['rules'];
        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($rules) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('argument')
                ->with('rules')
                ->once()
                ->andReturn($rules);
        });
        
        $result = $mock->getRules();

        $this->assertSame($expectedResult, $result, 'The rules are correct.');
    }

    /**
     * A test for getting the validaiton rule.
     *
     * @return void
     */
    public function test_get_validation_rule()
    {
        
    }
}