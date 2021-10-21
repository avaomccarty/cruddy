<?php

namespace Cruddy\Traits;

use Illuminate\Support\Str;

trait VueImportAddCommandTrait
{
    use CommandTrait;

    /**
     * Get new Cruddy Vue component name.
     *
     * @param  string|null  $style
     * @return string
     */
    protected function getComponent(string $style = null) : string
    {
        $name = $this->argument('name');
        $type = $this->getType();

        if ($style === 'kebab') {
            return Str::kebab($name) . '-' . strtolower($type);
        }

        return Str::studly($name) . Str::ucfirst($type);
    }

    /**
     * Get new Cruddy Vue component statements.
     *
     * @return string
     */
    protected function getComponentStatement() : string
    {
        return "Vue.component('" . $this->getComponent('kebab') . "', " . $this->getComponent() . ");\n";
    }

    /**
     * Get new Cruddy Vue import statement.
     *
     * @return string
     */
    protected function getImportStatement() : string
    {
        $lowerName = $this->getLowerSingular($this->argument('name'));
        $importString = "import " . $this->getComponent() . " from '@/components/" . $lowerName . "/" . $this->getType() . ".vue';\n";

        return $importString;
    }
}