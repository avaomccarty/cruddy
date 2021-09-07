<?php

namespace Cruddy\Traits;

use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

trait CommandTrait
{
    /**
     * Get the table.
     *
     * @return string
     */
    protected function getTable() : string
    {
        if (method_exists(self::class, 'argument')) {
            return (string)$this->argument('table') ?? '';
        }

        return '';
    }

    /**
     * Get new Cruddy Vue import statement.
     *
     * @return string
     */
    protected function getImportStatement() : string
    {
        if (method_exists(self::class, 'argument')) {
            $lowerName = strtolower(Str::studly(Str::singular(trim($this->argument('name')))));
            $studylyName = Str::studly($this->argument('name'));
            $ucFirstName = Str::ucfirst($this->getType());
            $importString = "import " . $studylyName . $ucFirstName . " from '@/components/" . $lowerName . "/" . $this->getType() . ".vue';\n";

            return $importString;
        }

        return '';
    }

    /**
     * Get new Cruddy Vue component statements.
     *
     * @return string
     */
    protected function getComponentStatement()
    {
        if (method_exists(self::class, 'argument')) {
            $studylyName = Str::studly($this->argument('name'));
            $ucFirstName = Str::ucfirst($this->getType());
            $componentStudlyName = $studylyName . $ucFirstName;

            $kebabName = Str::kebab($this->argument('name'));
            $componentKebabName = $kebabName . '-' . $this->getType();

            $componentString = "Vue.component('$componentKebabName', $componentStudlyName);";

            return $componentString . "\n";
        }

        return '';
    }

    /**
     * Get the type argument.
     *
     * @return string
     */
    public function getType() : string
    {
        if (property_exists(self::class, 'types') && is_array($this->types)) {
            if (in_array($this->argument('type'), $this->types)) {
                return $this->argument('type');
            }

            return $this->types[0];
        }

        if ($this->argument('type')) {
            if (in_array($this->argument('type'), $this->getDefaultTypes())) {
                return $this->argument('type');
            }
        }

        return $this->getDefaultTypes()[0];
    }

    /**
     * Get the default types.
     *
     * @return array
     */
    public function getDefaultTypes() : array
    {
        return [
            'index',
            'create',
            'show',
            'edit',
         ];
    }

    /**
     * Get the default input type.
     *
     * @return string
     */
    public function getDefaultInput() : string
    {
        return 'text';
    }

    /**
     * Get the input needed.
     *
     * @param  string  $input
     * @return string
     */
    protected function getInput(string $input) : string
    {
        if (array_key_exists($input, Config::get('cruddy.input_defaults'))) {
            return Config::get('cruddy.input_defaults.' . $input);
        }

        return $this->getDefaultInput();
    }

    /**
     * Get the inputs from the command.
     *
     * @return array
     */
    protected function getInputs() : array
    {
        if ($this->option('inputs')) {
            dd($this->option('inputs'));
            return (array)$this->option('inputs');
        }

        if ($this->argument('inputs')) {
            dd($this->argument('inputs'));
            return (array)$this->argument('inputs');
        }

        return [];
    }

    /**
     * Get the input needed as a string.
     *
     * @param  ColumnDefinition  $input
     * @return string
     */
    public function getInputString(ColumnDefinition $input) : string
    {
        $inputString = $this->files->get($this->getInputStub($input['type'])) . "\n\t\t";

        if (($this->getType() === 'edit' || $this->getType() === 'show') && Config::get('cruddy.frontend_scaffolding') === 'vue') {
            $inputString = str_replace(['DummyVModelName', '{{ vModelName }}', '{{vModelName}}'], 'item.' . $input['name'], $inputString);
        } else {
            $inputString = str_replace(['DummyVModelName', '{{ vModelName }}', '{{vModelName}}'], $input['name'], $inputString);
        }

        $inputString = str_replace(['DummyName', '{{ name }}', '{{name}}'], $input['name'], $inputString);
        $inputString = str_replace(['DummyData', '{{ data }}', '{{data}}'], $this->getExtraInputInfo($input), $inputString);

        return str_replace('  ', ' ', $inputString);
    }

    /**
     * Get the stub file.
     *
     * @return string
     */
    protected function getStub() : string
    {
        $folder = Config::get('cruddy.frontend_scaffolding') ?? 'default';

        return $this->resolveStubPath(Config::get('cruddy.stubs_folder') . '/views/' . $folder  . '/' . $this->getType() . '.stub');
    }

    /**
     * Get the stub input file.
     *
     * @param  string  $input
     * @return string
     */
    protected function getInputStub(string $input) : string
    {
        $folder = Config::get('cruddy.frontend_scaffolding') ?? 'default';

        return $this->resolveStubPath(Config::get('cruddy.stubs_folder') . '/views/' . $folder  . '/inputs/' . $this->getInput($input) . '.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub) : string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the submit input as a string.
     *
     * @return string
     */
    public function getSubmitInputString() : string
    {
        $inputString = $this->files->get($this->getInputStub('submit'));

        $inputString = str_replace(['DummyValue', '{{ value }}', '{{value}}'], 'Submit', $inputString);

        return str_replace('  ', ' ', $inputString);
    }

    /**
     * Turn the extra column data into a string of rules.
     *
     * @param  ColumnDefinition  $column
     * @return string
     */
    public function getExtraInputInfo(ColumnDefinition $column) : string
    {
        // dd('not hit?');
        $extraInputInfoString = '';

        if (($this->getType() === 'edit' || $this->getType() === 'show') && Config::get('cruddy.frontend_scaffolding') !== 'vue') {
            if (strlen($extraInputInfoString) > 0) {
                $extraInputInfoString .= ' ';
            }

            $extraInputInfoString .= 'value="{{ $' . $this->getClassName() . '->' . $column["name"] . ' }}"';
        }

        if ($this->getType() === 'show') {
            if (strlen($extraInputInfoString) > 0) {
                $extraInputInfoString .= ' ';
            }

            $extraInputInfoString .= 'disabled="disabled"';
        }

        if ($column->unsigned) {
            if (strlen($extraInputInfoString) > 0) {
                $extraInputInfoString .= ' ';
            }
            $extraInputInfoString .= 'min="0"';
        }

        if (($column->type == 'boolean' || $column->type == 'tinyInteger') && $column->default == 1) {
            if (strlen($extraInputInfoString) > 0) {
                $extraInputInfoString .= ' ';
            }
            $extraInputInfoString .= 'checked="checked"';
        }

        return $extraInputInfoString;
    }

    /**
     * Should the view include a submit input.
     *
     * @return boolean
     */
    public function needsSubmitInput() : bool
    {
        return $this->getType() !== 'index' && $this->getType() !== 'show' ;
    }

    /**
     * Replace the inputs for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceInputs(&$stub) : self
    {
        $inputs = $this->argument('inputs');

        $hasInput = false;
        $inputsString = '';

        foreach ($inputs as $input) {
            $hasInput = true;
            $inputsString .= $this->getInputString($input);
        }

        if ($hasInput && $this->needsSubmitInput()) {
            $inputsString .= $this->getSubmitInputString();
        }

        $stub = str_replace(['DummyInputs', '{{ inputs }}', '{{inputs}}'], $inputsString, $stub);

        return $this;
    }

    /**
     * Replace the model variable for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceModelVariable(&$stub) : self
    {
        $modelVariable = lcfirst(class_basename($this->argument('name')));

        $stub = str_replace(['DummyModelVariable', '{{ modelVariable }}', '{{modelVariable}}'], $modelVariable, $stub);

        return $this;
    }

    /**
     * Replace the rules for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceRules(&$stub)
    {
        $rules = $this->getRules();

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

        $stub = str_replace(['DummyRules', '{{ rules }}', '{{rules}}'], $rulesString, $stub);

        return $this;
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

    /**
     * Replace the action for the form request.
     *
     * @param  string  $stub
     * @return $this
     */
    public function replaceFormAction(&$stub) : self
    {
        $table = $this->getTable();

        if ($this->getType() === 'create') {
            $stub = str_replace(['DummyAction', '{{ action }}', '{{action}}'], '/' . $table, $stub);
        } else if ($this->getType() === 'edit') {
            if (Config::get('cruddy.frontend_scaffolding') === 'vue') {
                $route = "'/$table/' + this.item.id";
                $stub = str_replace(['DummyAction', '{{ action }}', '{{action}}'], $route, $stub);
            } else {
                $stub = str_replace(['DummyAction', '{{ action }}', '{{action}}'], '/' . $table . '/{{ $' . $this->getClassName() . '->id }}', $stub);
            }
        }

        if (Config::get('cruddy.frontend_scaffolding') === 'vue') {
            if ($this->getType() === 'index') {
                $stub = str_replace(['DummyAction', '{{ action }}', '{{action}}'], '/' . $table, $stub);
            }
        }


        return $this;
    }

    /**
     * Replace the editUrl for the the edit button.
     *
     * @param  string  $stub
     * @return $this
     */
    public function replaceFormEditUrl(&$stub) : self
    {
        $table = $this->getTable();

        if (Config::get('cruddy.frontend_scaffolding') === 'vue') {
            $editUrl = "'/$table/' + item.id + '/edit'";
            $stub = str_replace(['DummyEditUrl', '{{ editUrl }}', '{{editUrl}}'], $editUrl, $stub);
        } else {
            $stub = str_replace(['DummyEditUrl', '{{ editUrl }}', '{{editUrl}}'], '/' . $table . '/{{ $' . $this->getClassName() . '->id }}/edit', $stub);
        }

        return $this;
    }

    /**
     * Replace the cancelUrl for the the cancel button.
     *
     * @param  string  $stub
     * @return $this
     */
    public function replaceFormCancelUrl(&$stub) : self
    {
        $table = $this->getTable();

        $stub = str_replace(['DummyCancelUrl', '{{ cancelUrl }}', '{{cancelUrl}}'], '/' . $table, $stub);

        return $this;
    }

    /**
     * Get the props string for the stub.
     *
     * @return String
     */
    public function getPropsString()
    {
        if (method_exists(self::class, 'argument')) {
            $table = $this->getTable();
            $type = $this->getType();

            if ($type === 'index') {
                $prop = strtolower(Str::studly(trim($table)));
                return ' :prop-items="{{ json_encode($' . $prop . '->toArray()[\'data\']) }}"';
            } else if ($type === 'show' || $type === 'edit') {
                $table = $this->argument('table');
                $prop = strtolower(Str::studly(Str::singular(trim($table))));
                return ' :prop-item="{{ $' . $prop . ' }}"';
            }
        }

        return '';
    }

    /**
     * Replace the props variable for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceProps(&$stub)
    {
        $stub = str_replace(['DummyProps', '{{ props }}', '{{props}}'], $this->getPropsString(), $stub);

        return $this;
    }

    /**
     * Replace the variable name for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceVariableName(&$stub)
    {
        $type = $this->getType();

        if ($type === 'index') {
            $variableName = strtolower(Str::pluralStudly($this->getClassName()));
        } else {
            $variableName = strtolower(Str::studly($this->getClassName()));
        }

        $stub = str_replace(['DummyVariableName', '{{ VariableName }}', '{{VariableName}}'], $variableName ?? '', $stub);

        return $this;
    }

    /**
     * Replace the model variable for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceComponentNameVariable(&$stub)
    {
        if (method_exists(self::class, 'argument')) {
            $kebabName = Str::kebab($this->argument('name'));
            $componentName = $kebabName . '-' . $this->getType();

            $stub = str_replace(['DummyComponentName', '{{ componentName }}', '{{componentName}}'], $componentName, $stub);
        }

        return $this;
    }

    /**
     * Get the Vue data needed as a string.
     *
     * @param  ColumnDefinition  $input
     * @return string
     */
    public function getVueDataString(ColumnDefinition $input) : string
    {
        $vueDataString = '';
        $vueDataString .= $input['name'] . ': null,';
        $vueDataString .= "\n\t\t\t";

        return str_replace('  ', ' ', $vueDataString);
    }

    /**
     * Get the Vue post data needed as a string.
     *
     * @param  ColumnDefinition  $input
     * @return string
     */
    public function getVuePostDataString(ColumnDefinition $input) : string
    {
        $vuePostDataString = '';

        if ($this->getType() === 'edit') {
            $vuePostDataString .= $input['name'] . ': this.item.' . $input['name'] . ',';
        } else {
            $vuePostDataString .= $input['name'] . ': this.' . $input['name'] . ',';
        }

        $vuePostDataString .= "\n\t\t\t\t";
        return str_replace('  ', ' ', $vuePostDataString);
    }

    /**
     * Replace the Vue component name.
     *
     * @param  string  $stub
     * @return $this
     */
    public function replaceVueComponentName(&$stub) : self
    {
        if (Config::get('cruddy.frontend_scaffolding') === 'vue') {
            $studylyName = Str::studly(Str::singular($this->getTable()));
            $ucFirstName = Str::ucfirst($this->getType());
            $componentName = $studylyName . $ucFirstName;

            $stub = str_replace(['DummyComponentName', '{{ componentName }}', '{{componentName}}'], $componentName, $stub);
        }

        return $this;
    }

    /**
     * Replace the Vue data values.
     *
     * @param  string  $stub
     * @return $this
     */
    public function replaceVueData(&$stub) : self
    {
        if (Config::get('cruddy.frontend_scaffolding') === 'vue') {
            $inputs = $this->argument('inputs');

            $vueDataString = '';

            foreach ($inputs as $input) {
                $vueDataString .= $this->getVueDataString($input);
            }

            $stub = str_replace(['DummyVueData', '{{ vueData }}', '{{vueData}}'], $vueDataString, $stub);
        }

        return $this;
    }

    /**
     * Replace the Vue post data values.
     *
     * @param  string  $stub
     * @return $this
     */
    public function replaceVuePostData(&$stub) : self
    {
        if (Config::get('cruddy.frontend_scaffolding') === 'vue') {
            $inputs = $this->argument('inputs');

            $vuePostDataString = '';

            foreach ($inputs as $input) {
                $vuePostDataString .= $this->getVuePostDataString($input);
            }

            $stub = str_replace(['DummyVuePostData', '{{ vuePostData }}', '{{vuePostData}}'], $vuePostDataString, $stub);
        }

        return $this;
    }

}
