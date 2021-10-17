<?php

namespace Cruddy\Traits\Stubs;

use Cruddy\Traits\ConfigTrait;
use Illuminate\Database\Schema\ColumnDefinition;

trait RuleTrait
{
    use ConfigTrait;

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
        $rules = $this->getRules();

        $this->updateStubWithRules($stub, $rules);

        return $this;
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
        $hasRule = false;
        $rulesString = '';

        foreach ($rules as $rule) {
            $hasRule = true;
            if ($rule->name !== 'id') {
                $validationRules = '';
                if (method_exists(self::class, 'addDefaultValidationRules')) {
                    $this->addDefaultValidationRules($rule->type, $validationRules);
                }
                if (method_exists(self::class, 'addColumnValidationRules')) {
                    $this->addColumnValidationRules($rule, $validationRules);
                }

                $rulesString .= "'$rule->name' => '$validationRules',\n\t\t\t";
            }
        }

        if ($hasRule) {
            // Remove extra line break and tabs
            $rulesString = substr($rulesString, 0, -4);
        }

        $stub = str_replace($this->stubRulePlaceholders, $rulesString, $stub);
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
        $defaults = $this->getValidationDefault($type);

        if (strlen(trim($defaults)) > 0) {
            if (strlen(trim($validationRules)) > 0) {
                $validationRules .= '|';
            }

            $validationRules .= $defaults;
        }
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
            if (strpos($validationRules, 'min:') === false) {
                if (strlen(trim($validationRules)) > 0) {
                    $validationRules .= '|';
                }

                $validationRules .= 'min:0';
            }
            if (strpos($validationRules, 'integer') === false) {
                if (strlen(trim($validationRules)) > 0) {
                    $validationRules .= '|';
                }

                $validationRules .= 'integer';
            }
        }

        if ($column->nullable) {
            if (strpos($validationRules, 'nullable') === false) {
                if (strlen(trim($validationRules)) > 0) {
                    $validationRules = 'nullable|' . $validationRules;
                } else {
                    $validationRules = 'nullable';
                }
            }
        }
    }

    /**
     * Get the rules.
     *
     * @return array
     */
    public function getRules() : array
    {
        if (method_exists(self::class, 'argument')) {
            return (array)$this->argument('rules') ?? [];
        }

        return [];
    }
}