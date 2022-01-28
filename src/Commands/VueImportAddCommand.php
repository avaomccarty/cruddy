<?php

namespace Cruddy\Commands;

use Cruddy\CruddyCommand;
use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\ConsoleCommandTrait;

class VueImportAddCommand extends CruddyCommand
{
    use CommandTrait, ConsoleCommandTrait;

    /**
     * The type of stub editor.
     *
     * @var string
     */
    protected $stubEditorType = 'vue';

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
     * The success message.
     *
     * @var string
     */
    protected $successMessage = "Cruddy Vue import statements added successfully!\n";
}
