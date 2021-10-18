<?php

namespace Cruddy\Traits\Stubs;

use Cruddy\Traits\ConfigTrait;
use Illuminate\Database\Schema\ColumnDefinition;

trait RuleTrait
{
    use ConfigTrait;

    /**
     * The spacer used between validation rules.
     *
     * @var string
     */
    protected $ruleSpacer = '|';

    /**
     * The formatting at the end of the rule line.
     *
     * @var string
     */
    protected $endOfLineForRule = "\n\t\t\t";

    /**
     * The acceptable rule placeholders within a stub.
     *
     * @var array
     */
    protected $stubRulePlaceholders = [
        'DummyRules',
        '{{ rules }}',
        '{{rules}}'
    ];

    /**
     * Replace the rules for the given stub.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceRules(string &$stub) : self
    {
        $this->updateStubWithRules($stub, $this->getNonIdRules());

        return $this;
    }

    /**
     * Get the rules without ID columns.
     *
     * @return array
     */
    public function getNonIdRules() : array
    {
        return array_filter($this->getRules(), function ($rule) {
            return $rule->name !== 'id';
        });
    }

    /**
     * Get the rules.
     *
     * @return array
     */
    public function getRules() : array
    {
        return (array)$this->argument('rules') ?? [];
    }

    /**
     * Get a validation rule string from a rule.
     *
     * @param  ColumnDefinition  $rule
     * @return string
     */
    protected function getValidationRule(ColumnDefinition $rule, string $validationRules)
    {
        return "'$rule->name' => '$validationRules'," . $this->endOfLineForRule;
    }

    /**
     * Remove the formatting from the end of the rules.
     *
     * @param  string  &$rules
     * @return void
     */
    protected function removeFormattingFromEndOfRules(string &$rules) : void
    {
        if ($this->endsWithString($rules, $this->endOfLineForRule)) {
            $rules = substr($rules, 0, -strlen($this->endOfLineForRule));
        }
    }

    /**
     * Determine if the string ends with another string.
     *
     * @param  string  $haystack
     * @param  string  $needle
     * @return boolean
     */
    protected function endsWithString(string $haystack, string $needle) : bool
    {
        if (empty($needle) || empty($haystack)) {
            return false;
        }

        return substr($haystack, -strlen($needle)) === $needle;
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
            $this->includeMinOfZeroRule($validationRules);
            $this->includeIntegerRule($validationRules);
        }

        if ($column->nullable) {
            $this->includeNullableRule($validationRules);
        }
    }

    /**
     * Check if the string already contains a rule.
     *
     * @param  string  $rule
     * @return boolean
     */
    protected function hasRule(string $rules) : bool
    {
        return strlen(trim($rules)) > 0;
    }

    /**
     * Include one nullable validation rule within the rules.
     *
     * @param  string  &$rules
     * @return void
     */
    protected function includeNullableRule(string &$rules) : void
    {
        if (strpos($rules, 'nullable') === false) {
            $this->addNullableRule($rules);
        }
    }

    /**
     * Include one integer validation rule within the rules.
     *
     * @param  string  &$rules
     * @return void
     */
    protected function includeIntegerRule(string &$rules) : void
    {
        if (strpos($rules, 'integer') === false) {
            $this->addIntegerRule($rules);
        }
    }

    /**
     * Add an integer validation rule to the string.
     *
     * @param  string  &$rules
     * @return void
     */
    protected function addNullableRule(string &$rules) : void
    {
        $this->addRule('nullable', $rules);
    }

    /**
     * Add an integer validation rule to the string.
     *
     * @param  string  &$rules
     * @return void
     */
    protected function addIntegerRule(string &$rules) : void
    {
        $this->addRule($rules, 'integer');
    }

    /**
     * Include one minimum validation rule within the rules.
     *
     * @param  string  &$rules
     * @return void
     */
    protected function includeMinOfZeroRule(string &$rules) : void
    {
        if (strpos($rules, 'min:') === false) {
            $this->addMinOfZeroRule($rules);
        }
    }

    /**
     * Add a minimum validation rule to the string.
     *
     * @param  string  &$rules
     * @return void
     */
    protected function addMinOfZeroRule(string &$rules) : void
    {
        $this->addRule($rules, 'min:0');
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
        if ($this->hasRule($rules)) {
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
            $validationRules = '';
            $this->addDefaultValidationRules($rule->type, $validationRules);
            $this->addColumnValidationRules($rule, $validationRules);

            $rulesString .= $this->getValidationRule($rule, $validationRules);
        }

        $this->removeFormattingFromEndOfRules($rulesString);

        $stub = str_replace($this->stubRulePlaceholders, $rulesString, $stub);
    }
}