<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns;

use Cruddy\ForeignKeyDefinition;
use Cruddy\ForeignKeyValidation\ForeignKeyValidation;
use Cruddy\StubEditors\Inputs\Input\InputStubEditor;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\App;

class RequestStubInputEditor extends InputStubEditor
{

    /**
     * The formatting at the end of the line.
     *
     * @var string
     */
    protected $endOfLine = "\n\t\t\t";

    /**
     * The spacer used between validation rules.
     *
     * @var string
     */
    protected $ruleSpacer = '|';

    /**
     * Get the input as a string.
     *
     * @return string
     */
    public function getInputString() : string
    {
        $this->setColumnNameInValidation();
        $this->addDefaultColumnValidations();
        $this->addColumnValidations();
        $this->addForeignKeyRules();
        $this->addEndOfValidation();

        return $this->inputString;
    }

    /**
     * Add the end of the line 
     *
     * @return void
     */
    protected function addEndOfValidation() : void
    {
        $this->inputString .= "',";
        $this->addEndOfLineFormatting($this->inputString);
    }

    /**
     * Set the column name in the validation string.
     *
     * @return void
     */
    protected function setColumnNameInValidation() : void
    {
        $this->inputString .= $this->getColumnNameInValidation();
    }

    /**
     * Get the column name in the validation string.
     *
     * @return string
     */
    protected function getColumnNameInValidation() : string
    {
        return "'" . $this->column->name . "' => '";
    }

    /**
     * Check if the column has a foreign key attached.
     *
     * @return void
     */
    protected function addForeignKeyRules() : void
    {
        $columnForeignKeys = $this->getForeignKeysForColumn();

        foreach ($columnForeignKeys as $foreignKey) {
            $this->addCommandForeignKeyValidations($foreignKey);
        }
    }

    /**
     * Determine if the rule is a foreign key for the column name.
     *
     * @param \Cruddy\ForeignKeyDefinition  $foreignKey
     * @param  \Illuminate\Database\Schema\ColumnDefinition  $column
     * @param  string  $column
     * @return boolean
     */
    protected function isForeignKeyForColumn(ForeignKeyDefinition $foreignKey, ColumnDefinition $column) : bool
    {
        return in_array($column->name, $foreignKey->columns);
    }

    /**
     * Get the foreign keys for a column.
     *
     * @return array
     */
    protected function getForeignKeysForColumn() : array
    {
        return array_filter($this->foreignKeys, function ($foreignKey) {
            return $this->isForeignKeyForColumn($foreignKey, $this->column);
        });
    }

    /**
     * Add the default column validation rules.
     *
     * @return void
     */
    protected function addDefaultColumnValidations() : void
    {
        $this->includeRule($this->getValidationDefault($this->column->type));
    }

    /**
     * Add the specific column validation rules to the rule string.
     *
     * @return void
     */
    protected function addColumnValidations() : void
    {
        if ($this->column->unsigned) {
            $this->includeRule('min:0');
            $this->includeRule('integer');
        }

        if ($this->column->nullable) {
            $this->includeRule('nullable');
        }
    }

    /**
     * Add the specific foreign key validation rules to the rule string.
     *
     * @param \Cruddy\ForeignKeyDefinition  $foreignKey
     * @return void
     */
    protected function addCommandForeignKeyValidations(ForeignKeyDefinition $foreignKey) : void
    {
        $this->includeRule($this->getForeignKeyValidation($foreignKey));
    }

    /**
     * Get a validation rule string from a foreignKey.
     *
     * @param \Cruddy\ForeignKeyDefinition  $foreignKey
     * @return string
     */
    protected function getForeignKeyValidation(ForeignKeyDefinition $foreignKey) : string
    {
        return App::make(ForeignKeyValidation::class, [$foreignKey])
            ->getForeignKeyValidation();
    }

    /**
     * Include a rule within the rules.
     *
     * @param  string  $rule
     * @return void
     */
    protected function includeRule(string $rule) : void
    {
        if ($this->shouldAddRule($rule)) {
            $this->addRule($rule);
        }
    }

    /**
     * Determine if the rule should be added or not.
     *
     * @param  string  $rule
     * @return boolean
     */
    protected function shouldAddRule($rule) : bool
    {
        if (strpos($this->inputString, $rule) === false) {
            return true;
        }

        return $this->isMissingRule($rule) || $this->onlyHasThisRule($rule) && $this->isNotLastRule($rule) && $this->isNotFirstRule($rule);
    }

    /**
     * Determine if the rule provided is missing with the rules.
     *
     * @param  string  $rule
     * @return boolean
     */
    protected function isMissingRule(string $rule) : bool
    {
        return strpos($this->inputString, $rule) === false;
    }

    /**
     * Determine if the rule provided is not the last rule when there are already rules.
     *
     * @param  string  $rule
     * @return boolean
     */
    protected function isNotLastRule(string $rule) : bool
    {
        return strpos($this->inputString, $rule . $this->ruleSpacer) === false;
    }

    /**
     * Determine if the rule provided is not the first rule when there are already rules.
     *
     * @param  string  $rule
     * @return boolean
     */
    protected function isNotFirstRule(string $rule) : bool
    {
        return strpos($this->inputString, $this->ruleSpacer . $rule) === false;
    }

    /**
     * Determine if the rule provided is the only validation rule so far.
     *
     * @param  string  $rule
     * @return boolean
     */
    protected function onlyHasThisRule(string $rule) : bool
    {
        return strpos($this->inputString, "'$rule'") === false;
    }

    /**
     * Add a rule to the rules.
     *
     * @param  string  $rule
     * @return void
     */
    protected function addRule(string $rule) : void
    {
        if ($this->needsRuleSpacer()) {
            $this->addRuleSpacer();
        }

        $this->inputString .= $rule;
    }

    /**
     * Check if the validation rules already contains a rule.
     *
     * @return boolean
     */
    protected function needsRuleSpacer() : bool
    {
        return strlen(trim($this->inputString)) > 0 && $this->inputString !== $this->getColumnNameInValidation();
    }

    /**
     * Add a rule spacer to the validation rules.
     *
     * @return void
     */
    protected function addRuleSpacer() : void
    {
        $this->inputString .= $this->ruleSpacer;
    }
}