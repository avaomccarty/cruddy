<?php

namespace Cruddy;

use Cruddy\Traits\ConsoleCommandTrait;
use Illuminate\Console\Command;

class CruddyCommand extends Command
{
    use ConsoleCommandTrait;

    /**
     * The name of the new Cruddy resource.
     *
     * @var string
     */
    protected $name;

    /**
     * The type of stub editor.
     *
     * @var string
     */
    protected $stubEditorType = '';

    /**
     * The stub editor.
     *
     * @var \Cruddy\StubEditors\StubEditor|null
     */
    protected $stubEditor;

    /**
     * The success message.
     *
     * @var string
     */
    protected $successMessage = "Success!\n";

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
        $this->stubEditor->getUpdatedStub();
        $this->line($this->successMessage);

        return 0;
    }
}