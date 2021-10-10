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
        if (method_exists(self::class, 'argument')) {
            if ($style === 'kebab') {
                return Str::kebab($this->argument('name')) . '-' . strtolower($this->getType());
            } else {
                return Str::studly($this->argument('name')) . Str::ucfirst($this->getType());
            }
        }

        return '';
    }

    /**
     * Get new Cruddy Vue component statements.
     *
     * @return string
     */
    protected function getComponentStatement() : string
    {
        if (method_exists(self::class, 'argument')) {
            return "Vue.component('" . $this->getComponent('kebab') . "', " . $this->getComponent() . ");\n";
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
            $lowerName = $this->getLowerSingular($this->argument('name'));
            $importString = "import " . $this->getComponent() . " from '@/components/" . $lowerName . "/" . $this->getType() . ".vue';\n";

            return $importString;
        }

        return '';
    }
}