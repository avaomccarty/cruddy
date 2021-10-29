<?php

namespace Cruddy\Traits\Stubs;

use Illuminate\Database\Schema\ColumnDefinition;

trait RuleTrait
{
    use StubTrait;

    /**
     * The spacer used between validation rules.
     *
     * @var string
     */
    protected $ruleSpacer = '|';

    /**
     * The acceptable rule placeholders within a stub.
     *
     * @var array
     */
    protected $rulesPlaceholders = [
        'DummyRules',
        '{{ rules }}',
        '{{rules}}'
    ];

    /**
     * Get a validation rule string from a rule.
     *
     * @param  ColumnDefinition  $rule
     * @return string
     */
    protected function getValidationRule(ColumnDefinition $rule, string $validationRules)
    {
        $rule = "'$rule->name' => '$validationRules',";
        $this->addEndOfLineFormatting($rule);

        return $rule;
    }

    /**
     * Add the default column type validation rules to the validationRules string.
     *
     * @param  string  $type
     * @param  string  $validationRules
     * @return void
     */
    protected function addDefaultValidationRules(string $type = 'string', string &$validationRules = '') : void
    {
        $this->addRule($validationRules, $this->getValidationDefault($type));
    }

    /**
     * Add the specific column validation rules to the validationRules string.
     *
     * @param  ColumnDefinition  $column
     * @param  string  $validationRules
     * @return void
     */
    protected function addColumnValidationRules(ColumnDefinition $column, string &$validationRules = '') : void
    {
        if ($column->unsigned) {
            $this->includeRule($validationRules, 'min:0');
            $this->includeRule($validationRules, 'integer');
        }

        if ($column->nullable) {
            $this->includeRule($validationRules, 'nullable');
        }
    }

    /**
     * Check if the rules already contains a rule.
     *
     * @param  string  $rule
     * @return boolean
     */
    protected function hasARule(string $rules) : bool
    {
        return strlen(trim($rules)) > 0;
    }

    /**
     * Include a rule within the rules.
     *
     * @param  string  &$rules
     * @param  string  $rule
     * @return void
     */
    protected function includeRule(string &$rules, string $rule)
    {
        if (strpos($rules, $rule) === false) {
            $this->addRule($rules, $rule);
        }
    }

    /**
     * Add a rule to the rules.
     *
     * @param  string  &$rules
     * @param  string  $rule
     * @return void
     */
    protected function addRule(string &$rules, string $rule) : void
    {
        if ($this->hasARule($rules)) {
            $rules .= $this->ruleSpacer;
        }

        $rules .= $rule;
    }

    /**
     * Replace the rules for the given stub.
     *
     * @param  string  &$stub
     * @param  array  $rules
     * @return void
     */
    protected function updateStubWithRules(string &$stub, array $rules = []) : void
    {
        $rulesString = '';
        foreach ($rules as $rule) {
            $rulesString .= $this->getRuleString($rule);
        }

        $this->removeEndOfLineFormatting($rulesString);

        $this->replaceInStub($this->rulesPlaceholders, $rulesString, $stub);
    }

    /**
     * Get the rule string.
     *
     * @param  ColumnDefinition  $rule
     * @return string
     */
    protected function getRuleString(ColumnDefinition $rule) : string
    {
        $validationRules = '';
        $this->addDefaultValidationRules($rule->type, $validationRules);
        $this->addColumnValidationRules($rule, $validationRules);

        return $this->getValidationRule($rule, $validationRules);
    }
}