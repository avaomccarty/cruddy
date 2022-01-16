<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\ConsoleCommandTrait;
use Illuminate\Console\Command;

class VueImportAddCommand extends Command
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
     * The name of the new Cruddy resource.
     *
     * @var string
     */
    protected $name;

    /**
     * The stub editor.
     *
     * @var \Cruddy\StubEditors\VueStubEditor|null
     */
    protected $stubEditor;

    /**
     * The constructor method.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->setStubEditor();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->stubEditor->updateFile();
    }
}
