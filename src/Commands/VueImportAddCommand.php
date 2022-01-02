<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class VueImportAddCommand extends Command
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cruddy:vue-import
                            { name : The name of the resource that needs new Vue imports. }
                            { type=index : The type of file being created. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new Cruddy Vue import statements';

    /**
     * The name of the new Cruddy resource.
     *
     * @var string
     */
    protected $name;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $importStatement = $this->getImportStatement();
        $componentStatement = $this->getComponentStatement();
        $vueComponentSearch = $this->getVueComponentSearchString();

        if (File::exists($this->getVueImportFileLocation())) {
            $importFile = File::get($this->getVueImportFileLocation());

            // Add import statement to the top of the file if it does not already exist
            if (strpos($importFile, $importStatement) === false) {
                File::prepend($this->getVueImportFileLocation(), $importStatement);
            }

            // Add Vue.component() statements to the file if they do not already exist
            if (strpos($importFile, $componentStatement) === false) {
                $positionInFile = strpos($importFile, $vueComponentSearch);

                if ($positionInFile !== false && is_numeric($positionInFile)) {
                    $updatedFile = substr_replace($importFile, $componentStatement, $positionInFile, 0);
                    File::put($this->getVueImportFileLocation(), $updatedFile);
                }
            }
        }
    }

    /**
     * Get new Cruddy Vue component name.
     *
     * @param  string|null  $style
     * @return string
     */
    protected function getComponent(string $style = null) : string
    {
        $name = $this->getResourceName();
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
        $name = $this->getResourceName();
        $lowerName = $this->getLowerSingular($name);
        $importString = "import " . $this->getComponent() . " from '@/components/" . $lowerName . "/" . $this->getType() . ".vue';\n";

        return $importString;
    }
}
