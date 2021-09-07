<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class VueImportAddCommand extends Command
{
    use CommandTrait;

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
    protected $description = 'Add new Cruddy Vue import statements';

    /**
     * The name of the new Cruddy resource.
     *
     * @var string
     */
    protected $name;

    /**
     * The string to search for within the vue_import_file to add other Vue components.
     *
     * @var array
     */
    protected $possibleSearchesArray = [
        'Vue.component(',
        'const app = new Vue('
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $importStatement = $this->getImportStatement();
        $componentStatement = $this->getComponentStatement();

        if (File::exists(Config::get('cruddy.vue_import_file'))) {
            $importFile = File::get(Config::get('cruddy.vue_import_file'));

            // Add import statement to the top of the file if it does not already exist
            if (strpos($importFile, $importStatement) !== false) {
                echo "No Cruddy Vue imports were added.\n";
            } else {
                File::prepend(Config::get('cruddy.vue_import_file'), $importStatement);
                echo "Cruddy Vue imports were added successfully!\n";
            }

            // Add Vue.component() statements to the file if they do not already exist
            if (strpos($importFile, $componentStatement) !== false) {
                echo "No Cruddy Vue components were added.\n";
            } else {
                foreach ($this->possibleSearchesArray as $search) {
                    $positionInFile = strpos($importFile, $search);
                    if ($positionInFile !== false && is_numeric($positionInFile)) {
                        $updatedFile = substr_replace($importFile, $componentStatement, $positionInFile, 0);
                        File::put(Config::get('cruddy.vue_import_file'), $updatedFile);
                        echo "Cruddy Vue components were added successfully!\n";
                        break;
                    } else {
                        echo "Could not find the following string in your file: " . $search . "\n";
                        if ($search !== last($this->possibleSearchesArray)) {
                            echo "Trying another option...\n";
                        } else {
                            echo "Could not find other options to try. Please check the 'vue_import_file' file for one of the following options: " . $this->possibleSearchesArray . "\n";
                        }
                    }
                }
            }
        }
    }
}
