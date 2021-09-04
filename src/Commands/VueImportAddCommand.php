<?php

namespace Cruddy\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class VueImportAddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cruddy:vue-import
                            {name : The name of the resource that needs new vue imports}
                            {type=index}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new Cruddy vue import statements';

    /**
     * The name of the new Cruddy resource.
     *
     * @var string
     */
    protected $name;

    /**
     * The acceptable types of views.
     *
     * @var array
     */
    protected $types = [
        'index',
        'create',
        'show',
        'edit',
    ];


    /**
     * Get the type of request being created.
     *
     * @return string
     */
    public function getType()
    {
        if (in_array($this->argument('type'), $this->types)) {
            return $this->argument('type');
        }

        return $this->types[0];
    }

    /**
     * Get new Cruddy vue import statement.
     *
     * @return string
     */
    protected function getImportStatement()
    {
        $lowerName = Str::plural(strtolower($this->argument('name')));
        $studylyName = Str::studly($this->argument('name'));
        $importString = "import " . $studylyName . " from @/components/" . $lowerName . '/' . $this->getType() . '.vue'
                        . "\n\n";

        return $importString . ';';
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $importStatement = $this->getImportStatement();


        if (File::exists(Config::get('cruddy.vue_import_file'))) {
            $importFile = File::get(Config::get('cruddy.vue_import_file'));

            if (strpos($importFile, $importStatement) !== false) {
                echo "No Cruddy vue imports were added.\n";
            } else {
                File::prepend(Config::get('cruddy.vue_import_file'), $importStatement);
                echo "Cruddy vue imports were added successfully!\n";
            }
        }
    }
}
