<?php

namespace Cruddy\Tests\Unit\Traits\Stubs;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\Stubs\RuleTrait;
use Illuminate\Database\Schema\ColumnDefinition;
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
        $rule = $this->getMockColumns()[0];
        $validationRules = 'validationRules';
        $expectedResult = "'$rule->name' => '$validationRules'," . $this->endOfLineForRule;

        $result = $this->getValidationRule($rule, $validationRules);

        $this->assertSame($expectedResult, $result, 'The validatin rule string does not match.');
    }

    /**
     * A test to remove unneeded formatting at the end of the string.
     *
     * @return void
     */
    public function test_remove_formatting_at_end_of_rules()
    {
        $expectedResult = $rules = 'rules';
        $rules = $rules . $this->endOfLineForRule;

        $this->removeFormattingFromEndOfRules($rules);

        $this->assertSame($expectedResult, $rules, 'The string does not match the expected result.');
        $this->assertFalse(strpos($rules, $this->endOfLineForRule), 'The formatting was found within the result.');
    }

    /**
     * A test for needs formatting removed when formatting needs to be removed.
     *
     * @return void
     */
    public function test_needs_formatting_removed_when_formatting_needs_removed()
    {
        $rules = 'rules' . $this->endOfLineForRule;
        
        $result = $this->needsFormattingRemoved($rules);

        $this->assertTrue($result);
    }

    /**
     * A test for needs formatting removed when formatting does not need to be removed.
     *
     * @return void
     */
    public function test_needs_formatting_removed_when_formatting_does_not_need_removed()
    {
        $rules = $this->endOfLineForRule . 'rules';
        
        $result = $this->needsFormattingRemoved($rules);

        $this->assertFalse($result);
    }

    /**
     * A test for needs formatting removed when empty string used.
     *
     * @return void
     */
    public function test_needs_formatting_removed_when_empty_string_used()
    {
        $rules = '';
        
        $result = $this->needsFormattingRemoved($rules);

        $this->assertFalse($result);
    }

    /**
     * A test to add the default validation rules.
     *
     * @return void
     */
    public function test_add_default_validation_rules()
    {
        $type = 'string';
        $validationRules = 'validationRules';
        $getValidationDefault = 'getValidationDefault';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($getValidationDefault, $type, $validationRules) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getValidationDefault')
                ->with($type)
                ->once()
                ->andReturn($getValidationDefault);
            $mock->shouldReceive('addRule')
                ->with($validationRules, $getValidationDefault)
                ->once();
        });
        
        $mock->addDefaultValidationRules($type, $validationRules);
    }

    /**
     * A test for adding default column validation rules to unsigned column.
     *
     * @return void
     */
    public function test_add_column_validation_rules_for_unsigned_column()
    {
        $column = new ColumnDefinition([
            'unsigned' => true
        ]);
        $validationRules = 'validationRules';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($validationRules) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('includeRule')
                ->with($validationRules, 'min:0')
                ->once();
            $mock->shouldReceive('includeRule')
                ->with($validationRules, 'integer')
                ->once();
            $mock->shouldReceive('includeRule')
                ->with($validationRules, 'nullable')
                ->never();
        });
        
        $mock->addColumnValidationRules($column, $validationRules);
    }

    /**
     * A test for adding default column validation rules to nullable column.
     *
     * @return void
     */
    public function test_add_column_validation_rules_for_nullable_column()
    {
        $column = new ColumnDefinition([
            'nullable' => true
        ]);
        $validationRules = 'validationRules';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($validationRules) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('includeRule')
                ->with($validationRules, 'min:0')
                ->never();
            $mock->shouldReceive('includeRule')
                ->with($validationRules, 'integer')
                ->never();
            $mock->shouldReceive('includeRule')
                ->with($validationRules, 'nullable')
                ->once();
        });
        
        $mock->addColumnValidationRules($column, $validationRules);
    }

    /**
     * A test for adding default column validation rules to nullable and unsigned column.
     *
     * @return void
     */
    public function test_add_column_validation_rules_for_nullable_and_unsigned_column()
    {
        $column = new ColumnDefinition([
            'unsigned' => true,
            'nullable' => true
        ]);
        $validationRules = 'validationRules';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($validationRules) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('includeRule')
                ->with($validationRules, 'min:0')
                ->once();
            $mock->shouldReceive('includeRule')
                ->with($validationRules, 'integer')
                ->once();
            $mock->shouldReceive('includeRule')
                ->with($validationRules, 'nullable')
                ->once();
        });
        
        $mock->addColumnValidationRules($column, $validationRules);
    }

    /**
     * A test for adding default column validation rules to neither nullable nor unsigned column.
     *
     * @return void
     */
    public function test_add_column_validation_rules_for_neither_nullable_nor_unsigned_column()
    {
        $column = new ColumnDefinition([
            'unsigned' => false,
            'nullable' => false
        ]);
        $validationRules = 'validationRules';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($validationRules) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('includeRule')
                ->never();
            $mock->shouldReceive('includeRule')
                ->never();
            $mock->shouldReceive('includeRule')
                ->never();
        });
        
        $mock->addColumnValidationRules($column, $validationRules);
    }

    /**
     * A test has rule when there is a rule.
     *
     * @return void
     */
    public function test_has_rule_when_rules_exist()
    {
        $rules = 'rules';

        $result = $this->hasARule($rules);

        $this->assertTrue($result);
    }

    /**
     * A test has rule when there is not a rule.
     *
     * @return void
     */
    public function test_has_rule_when_no_rules_exist()
    {
        $rules = [
            ' ',
            '',
        ];

        foreach ($rules as $rule) {
            $result = $this->hasARule($rule);

            $this->assertFalse($result);
        }
    }

    /**
     * A test adding a rule that already exists within the rules.
     *
     * @return void
     */
    public function test_include_rule_that_already_exists()
    {
        $rule = $rules = 'rules';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('addRule')
                ->never();
        });

        $mock->includeRule($rules, $rule);
    }

    /**
     * A test adding a rule that does not already exists within the rules.
     *
     * @return void
     */
    public function test_include_rule_that_does_not_already_exists()
    {
        $rule = 'xyz';
        $rules = 'rules';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($rules, $rule) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('addRule')
                ->with($rules, $rule)
                ->once();
        });

        $mock->includeRule($rules, $rule);
    }

    /**
     * A test to add first rule to rules.
     *
     * @return void
     */
    public function test_add_rule_to_empty_rules()
    {
        $rules = '';
        $rule = 'rule';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($rules) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('hasARule')
                ->with($rules)
                ->once()
                ->andReturn(false);
        });
        
        $mock->addRule($rules, $rule);

        $this->assertSame($rule, $rules, 'The rule should be the same as the rules since it is the only rule.');
    }

    /**
     * A test to add new rule to other rules.
     *
     * @return void
     */
    public function test_add_rule_to_non_empty_rules()
    {
        $rules = 'rules';
        $rule = 'rule';
        $expectedRules = $rules . $this->ruleSpacer . $rule;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($rules) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('hasARule')
                ->with($rules)
                ->once()
                ->andReturn(true);
        });
        
        $mock->addRule($rules, $rule);

        $this->assertSame($expectedRules, $rules, 'The new rule was not added to the rules correctly.');
    }

    /**
     * A test to update the stub with the rules.
     *
     * @return void
     */
    public function test_update_stub_with_rules()
    {
        $originalStub = $expectedStub = $stub = 'stub-';
        $rules = $this->getMockColumns();
        
        $expectedRulesString = '';
        foreach ($rules as $rule) {
            $expectedRulesString .= $rule->name;
        }

        foreach ($this->stubRulePlaceholders as $placeholder) {
            $stub .= $placeholder;
            $expectedStub .= $expectedRulesString;
        }

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($rules, $expectedRulesString) {
            $mock->shouldAllowMockingProtectedMethods();
            foreach ($rules as $rule) {
                $mock->shouldReceive('getRuleString')
                    ->with($rule)
                    ->once()
                    ->andReturn($rule->name);
            }
            $mock->shouldReceive('removeFormattingFromEndOfRules')
                ->with($expectedRulesString)
                ->once();
        });

        $mock->updateStubWithRules($stub, $rules);

        $this->assertNotSame($originalStub, $stub, 'The stub should have been updated.');
        $this->assertSame($expectedStub, $stub, 'The stub did not match the expected stub.');
    }

    /**
     * A test to get the rule string.
     *
     * @return void
     */
    public function test_get_rule_string()
    {
        $rule = new ColumnDefinition([
            'type' => 'type'
        ]);
        $expectedResult = 'expectedResult';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($rule, $expectedResult) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('addDefaultValidationRules')
                ->with($rule->type, '')
                ->once();
            $mock->shouldReceive('addColumnValidationRules')
                ->with($rule, '')
                ->once();
            $mock->shouldReceive('getValidationRule')
                ->with($rule, '')
                ->once()
                ->andReturn($expectedResult);
        });
        
        $result = $mock->getRuleString($rule);

        $this->assertSame($expectedResult, $result);
    }
}