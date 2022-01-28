<?php

namespace Cruddy\Tests\Feature;

use Cruddy\StubEditors\Inputs\Input\InputStub;
use Cruddy\StubEditors\Inputs\Input\Columns\ViewColumnInputStub;
use Cruddy\StubEditors\Inputs\StubInputsEditor;
use Cruddy\StubEditors\StubEditor;
use Cruddy\StubEditors\ViewStub;
use Cruddy\Tests\TestTrait;
use Cruddy\Traits\CommandTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;

class ViewMakeCommandTest extends TestCase
{
    use TestTrait, CommandTrait;

    /**
     * The name of the resource.
     *
     * @var string
     */
    protected $name = 'name';

    /**
     * The name of the table in the migration.
     *
     * @var string
     */
    protected $table = 'table';

    /**
     * Get an acceptable array of input default types.
     *
     * @return array
     */
    protected function getInputDefaults() : array
    {
        return [
            'bigInteger' => 'number',
            'integer' => 'number',
            'string' => 'text',
            'submit' => 'submit',
        ];
    }

    /**
     * Get an acceptable input type.
     *
     * @param  string  $input = 'string'
     * @return string
     */
    protected function getInputDefault(string $input = 'string') : string
    {
        return $this->getInputDefaults()[$input];
    }

    /**
     * A test for correct Vue index view file.
     *
     * @return
     */
    public function test_correct_vue_index_view_file_created()
    {
        $type = 'index';
        $viewType = 'vue';
        $inputs = $this->getMockColumns();
        $expectedStubLocation = base_path() . "/stubs/views/$viewType/page.stub";
        $stubLocation = dirname(dirname(__DIR__)) . "/Commands/stubs/views/$viewType/$type.stub";
        $stub = File::get($stubLocation);
        $expectedBladeFileLocation = "stubs/Table/$type.vue";
        $expectedBladeFile = File::get(dirname(__DIR__) . "/stubs/views/$viewType/expectedBladeFile" . ucfirst($type) . ".stub");

        $inputsLocation = dirname(dirname(__DIR__)) . "/Commands/stubs/views/$viewType/inputs/";

        foreach ($inputs as $input) {
            $input->mockInputStub = File::get($inputsLocation . $this->getInputDefault($input->type) . ".stub");
        }

        // Assert the StubEditor is created correctly.
        $stubEditor = new ViewStub();
        App::shouldReceive('make')
            ->with(StubEditor::class, ['view'])
            ->once()
            ->andReturn($stubEditor);

        // Assert the StubInputsEditor is created correctly.
        App::shouldReceive('make')
            ->with(StubInputsEditor::class, [$inputs, 'view'])
            ->once()
            ->andReturn(new StubInputsEditor($inputs, 'view'));

        // Assert config frontend scaffolding is used.
        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->times(16)
            ->andReturn($viewType);
        
        // Assert config stubs folder is used.
        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->times(4)
            ->andReturn('stubs');

        // Assert config stubs folder is used.
        Config::shouldReceive('get')
            ->with('cruddy.vue_folder')
            ->times(1)
            ->andReturn('stubs');
        
        // Assert config input defaults is used.
        Config::shouldReceive('get')
            ->with('cruddy.input_defaults')
            ->times(6)
            ->andReturn($this->getInputDefaults());

        // Assert config input default is used for each input.
        foreach ($inputs as $input) {
            App::shouldReceive('make')
                ->with(StubInputEditor::class, [$input, 'view', '', false])
                ->once()
                ->andReturn(new ViewColumnInputStub($input));
        }

        Config::partialMock();

        // Assert stub at the location exists for file type.
        File::shouldReceive('exists')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn(true);

        // Assert getting the correct stub file for file type.
        File::shouldReceive('get')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn($stub);
        
        foreach ($inputs as $input) {
            $location = base_path() . "/stubs/views/$viewType/inputs/" . $this->getInputDefault($input->type) . ".stub";
            // Assert stub at the location exists.
            File::shouldReceive('exists')
                ->with($location)
                ->once()
                ->andReturn(true);

            // Assert getting the correct stub file for file type.
            File::shouldReceive('get')
                ->with($location)
                ->once()
                ->andReturn($input->mockInputStub);
        }

        // Assert correct blade file is created in the correct location.
        File::shouldReceive('put')
            ->with($expectedBladeFileLocation, $expectedBladeFile)
            ->once()
            ->andReturn(true);

        File::partialMock();

        $this->artisan('cruddy:view', [
            'name' => $this->name,
            'table' => $this->table,
            'type' => $type,
            'inputs' => $inputs,
            '--force' => true,
        ]);

        $placeholdersArray = [
            $stubEditor->inputPlaceholders,
            $stubEditor->actionPlaceholders,
            $stubEditor->editUrlPlaceholders,
            $stubEditor->variableCollectionPlaceholders,
            $stubEditor->variablePlaceholders,
            $stubEditor->cancelUrlPlaceholders,
            $stubEditor->modelPlaceholders,
            $stubEditor->vueComponentPlaceholders,
            $stubEditor->vueDataPlaceholders,
            $stubEditor->vuePostDataPlaceholders,
        ];

        // Assert that the expected blade file does not have any stub model placeholders remaining
        foreach ($placeholdersArray as $placeholders) {
            foreach ($placeholders as $placeholder) {
                $this->assertFalse(strpos($expectedBladeFile, $placeholder));
            }
        }
    }

    /**
     * A test for correct Vue create view file.
     *
     * @return
     */
    public function test_correct_vue_create_view_file_created()
    {
        $type = 'create';
        $viewType = 'vue';
        $inputs = $this->getMockColumns();
        $expectedStubLocation = base_path() . "/stubs/views/$viewType/page.stub";
        $stubLocation = dirname(dirname(__DIR__)) . "/Commands/stubs/views/$viewType/$type.stub";
        $stub = File::get($stubLocation);
        $expectedBladeFileLocation = "stubs/Table/$type.vue";
        $expectedBladeFile = File::get(dirname(__DIR__) . "/stubs/views/$viewType/expectedBladeFile" . ucfirst($type) . ".stub");

        $inputsLocation = dirname(dirname(__DIR__)) . "/Commands/stubs/views/$viewType/inputs/";

        foreach ($inputs as $input) {
            $input->mockInputStub = File::get($inputsLocation . $this->getInputDefault($input->type) . ".stub");
        }
        $mockSubmitInputStub = File::get($inputsLocation  . "submit.stub");

        // Assert the StubEditor is created correctly.
        $stubEditor = new ViewStub();
        App::shouldReceive('make')
            ->with(StubEditor::class, ['view'])
            ->once()
            ->andReturn($stubEditor);

        // Assert the StubInputsEditor is created correctly.
        App::shouldReceive('make')
            ->with(StubInputsEditor::class, [$inputs, 'view'])
            ->once()
            ->andReturn(new StubInputsEditor($inputs, 'view'));

        // Assert config frontend scaffolding is used.
        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->times(18)
            ->andReturn($viewType);
        
        // Assert config stubs folder is used.
        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->times(5)
            ->andReturn('stubs');
        
        // Assert config stubs folder is used.
        Config::shouldReceive('get')
            ->with('cruddy.vue_folder')
            ->times(1)
            ->andReturn('stubs');
        
        // Assert config input defaults is used.
        Config::shouldReceive('get')
            ->with('cruddy.input_defaults')
            ->times(8)
            ->andReturn($this->getInputDefaults());

        // Assert config input default is used for each input.
        foreach ($inputs as $input) {
            App::shouldReceive('make')
                ->with(StubInputEditor::class, [$input, 'view', '', true])
                ->once()
                ->andReturn(new ViewColumnInputStub($input));
        }

        // Assertion for submit input
        App::shouldReceive('make')
            ->with(StubInputEditor::class, [null, 'view', '', true])
            ->once()
            ->andReturn(new ViewColumnInputStub());

        Config::partialMock();

        // Assert stub at the location exists for file type.
        File::shouldReceive('exists')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn(true);

        // Assert getting the correct stub file for file type.
        File::shouldReceive('get')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn($stub);
        
        foreach ($inputs as $input) {
            $location = base_path() . "/stubs/views/$viewType/inputs/" . $this->getInputDefault($input->type) . ".stub";
            // Assert stub at the location exists.
            File::shouldReceive('exists')
                ->with($location)
                ->once()
                ->andReturn(true);

            // Assert getting the correct stub file for file type.
            File::shouldReceive('get')
                ->with($location)
                ->once()
                ->andReturn($input->mockInputStub);
        }

        // Assert file uses submit input stub
        $location = base_path() . "/stubs/views/$viewType/inputs/submit.stub";
        File::shouldReceive('exists')
            ->with($location)
            ->once()
            ->andReturn(true);
    
        File::shouldReceive('get')
            ->with($location)
            ->once()
            ->andReturn($mockSubmitInputStub);

        // Assert correct blade file is created in the correct location.
        File::shouldReceive('put')
            ->with($expectedBladeFileLocation, $expectedBladeFile)
            ->once()
            ->andReturn(true);

        File::partialMock();

        $this->artisan('cruddy:view', [
            'name' => $this->name,
            'table' => $this->table,
            'type' => $type,
            'inputs' => $inputs,
            '--force' => true,
        ]);

        $placeholdersArray = [
            $stubEditor->inputPlaceholders,
            $stubEditor->actionPlaceholders,
            $stubEditor->editUrlPlaceholders,
            $stubEditor->variableCollectionPlaceholders,
            $stubEditor->variablePlaceholders,
            $stubEditor->cancelUrlPlaceholders,
            $stubEditor->modelPlaceholders,
            $stubEditor->vueComponentPlaceholders,
            $stubEditor->vueDataPlaceholders,
            $stubEditor->vuePostDataPlaceholders,
        ];

        // Assert that the expected blade file does not have any stub model placeholders remaining
        foreach ($placeholdersArray as $placeholders) {
            foreach ($placeholders as $placeholder) {
                $this->assertFalse(strpos($expectedBladeFile, $placeholder));
            }
        }
    }

    /**
     * A test for correct Vue show view file.
     *
     * @return
     */
    public function test_correct_vue_show_view_file_created()
    {
        $type = 'show';
        $viewType = 'vue';
        $inputs = $this->getMockColumns();
        $expectedStubLocation = base_path() . "/stubs/views/$viewType/page.stub";
        $stubLocation = dirname(dirname(__DIR__)) . "/Commands/stubs/views/$viewType/$type.stub";
        $stub = File::get($stubLocation);
        $expectedBladeFileLocation = "stubs/Table/$type.vue";
        $expectedBladeFile = File::get(dirname(__DIR__) . "/stubs/views/$viewType/expectedBladeFile" . ucfirst($type) . ".stub");

        $inputsLocation = dirname(dirname(__DIR__)) . "/Commands/stubs/views/$viewType/inputs/";

        foreach ($inputs as $input) {
            $input->mockInputStub = File::get($inputsLocation . $this->getInputDefault($input->type) . ".stub");
        }

        // Assert the StubEditor is created correctly.
        $stubEditor = new ViewStub();
        App::shouldReceive('make')
            ->with(StubEditor::class, ['view'])
            ->once()
            ->andReturn($stubEditor);

        // Assert the StubInputsEditor is created correctly.
        App::shouldReceive('make')
            ->with(StubInputsEditor::class, [$inputs, 'view'])
            ->once()
            ->andReturn(new StubInputsEditor($inputs, 'view'));

        // Assert config frontend scaffolding is used.
        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->times(15)
            ->andReturn($viewType);
        
        // Assert config stubs folder is used.
        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->times(4)
            ->andReturn('stubs');
        
        // Assert config stubs folder is used.
        Config::shouldReceive('get')
            ->with('cruddy.vue_folder')
            ->times(1)
            ->andReturn('stubs');
        
        // Assert config input defaults is used.
        Config::shouldReceive('get')
            ->with('cruddy.input_defaults')
            ->times(6)
            ->andReturn($this->getInputDefaults());

        // Assert config input default is used for each input.
        foreach ($inputs as $input) {
            App::shouldReceive('make')
                ->with(StubInputEditor::class, [$input, 'view', '', false])
                ->once()
                ->andReturn(new ViewColumnInputStub($input));
        }

        Config::partialMock();

        // Assert stub at the location exists for file type.
        File::shouldReceive('exists')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn(true);

        // Assert getting the correct stub file for file type.
        File::shouldReceive('get')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn($stub);
        
        foreach ($inputs as $input) {
            $location = base_path() . "/stubs/views/$viewType/inputs/" . $this->getInputDefault($input->type) . ".stub";
            // Assert stub at the location exists.
            File::shouldReceive('exists')
                ->with($location)
                ->once()
                ->andReturn(true);

            // Assert getting the correct stub file for file type.
            File::shouldReceive('get')
                ->with($location)
                ->once()
                ->andReturn($input->mockInputStub);
        }

        // Assert correct blade file is created in the correct location.
        File::shouldReceive('put')
            ->with($expectedBladeFileLocation, $expectedBladeFile)
            ->once()
            ->andReturn(true);

        File::partialMock();

        $this->artisan('cruddy:view', [
            'name' => $this->name,
            'table' => $this->table,
            'type' => $type,
            'inputs' => $inputs,
            '--force' => true,
        ]);

        $placeholdersArray = [
            $stubEditor->inputPlaceholders,
            $stubEditor->actionPlaceholders,
            $stubEditor->editUrlPlaceholders,
            $stubEditor->variableCollectionPlaceholders,
            $stubEditor->variablePlaceholders,
            $stubEditor->cancelUrlPlaceholders,
            $stubEditor->modelPlaceholders,
            $stubEditor->vueComponentPlaceholders,
            $stubEditor->vueDataPlaceholders,
            $stubEditor->vuePostDataPlaceholders,
        ];

        // Assert that the expected blade file does not have any stub model placeholders remaining
        foreach ($placeholdersArray as $placeholders) {
            foreach ($placeholders as $placeholder) {
                $this->assertFalse(strpos($expectedBladeFile, $placeholder));
            }
        }
    }

    /**
     * A test for correct Vue edit view file.
     *
     * @return
     */
    public function test_correct_vue_edit_view_file_created()
    {
        $type = 'edit';
        $viewType = 'vue';
        $inputs = $this->getMockColumns();
        $expectedStubLocation = base_path() . "/stubs/views/$viewType/page.stub";
        $stubLocation = dirname(dirname(__DIR__)) . "/Commands/stubs/views/$viewType/$type.stub";
        $stub = File::get($stubLocation);
        $expectedBladeFileLocation = "stubs/Table/$type.vue";
        $expectedBladeFile = File::get(dirname(__DIR__) . "/stubs/views/$viewType/expectedBladeFile" . ucfirst($type) . ".stub");

        $inputsLocation = dirname(dirname(__DIR__)) . "/Commands/stubs/views/$viewType/inputs/";

        foreach ($inputs as $input) {
            $input->mockInputStub = File::get($inputsLocation . $this->getInputDefault($input->type) . ".stub");
        }
        $mockSubmitInputStub = File::get($inputsLocation  . "submit.stub");

        // Assert the StubEditor is created correctly.
        $stubEditor = new ViewStub();
        App::shouldReceive('make')
            ->with(StubEditor::class, ['view'])
            ->once()
            ->andReturn($stubEditor);

        // Assert the StubInputsEditor is created correctly.
        App::shouldReceive('make')
            ->with(StubInputsEditor::class, [$inputs, 'view'])
            ->once()
            ->andReturn(new StubInputsEditor($inputs, 'view'));

        // Assert config frontend scaffolding is used.
        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->times(19)
            ->andReturn($viewType);
        
        // Assert config stubs folder is used.
        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->times(5)
            ->andReturn('stubs');
    
        // Assert config stubs folder is used.
        Config::shouldReceive('get')
            ->with('cruddy.vue_folder')
            ->times(1)
            ->andReturn('stubs');
        
        // Assert config input defaults is used.
        Config::shouldReceive('get')
            ->with('cruddy.input_defaults')
            ->times(8)
            ->andReturn($this->getInputDefaults());

        // Assert config input default is used for each input.
        foreach ($inputs as $input) {
            App::shouldReceive('make')
                ->with(StubInputEditor::class, [$input, 'view', '', true])
                ->once()
                ->andReturn(new ViewColumnInputStub($input));
        }

        // Assertion for submit input
        App::shouldReceive('make')
            ->with(StubInputEditor::class, [null, 'view', '', true])
            ->once()
            ->andReturn(new ViewColumnInputStub());

        Config::partialMock();

        // Assert stub at the location exists for file type.
        File::shouldReceive('exists')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn(true);

        // Assert getting the correct stub file for file type.
        File::shouldReceive('get')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn($stub);
        
        foreach ($inputs as $input) {
            $location = base_path() . "/stubs/views/$viewType/inputs/" . $this->getInputDefault($input->type) . ".stub";
            // Assert stub at the location exists.
            File::shouldReceive('exists')
                ->with($location)
                ->once()
                ->andReturn(true);

            // Assert getting the correct stub file for file type.
            File::shouldReceive('get')
                ->with($location)
                ->once()
                ->andReturn($input->mockInputStub);
        }

        // Assert file uses submit input stub
        $location = base_path() . "/stubs/views/$viewType/inputs/submit.stub";
        File::shouldReceive('exists')
            ->with($location)
            ->once()
            ->andReturn(true);
    
        File::shouldReceive('get')
            ->with($location)
            ->once()
            ->andReturn($mockSubmitInputStub);

        // Assert correct blade file is created in the correct location.
        File::shouldReceive('put')
            ->with($expectedBladeFileLocation, $expectedBladeFile)
            ->once()
            ->andReturn(true);

        File::partialMock();

        $this->artisan('cruddy:view', [
            'name' => $this->name,
            'table' => $this->table,
            'type' => $type,
            'inputs' => $inputs,
            '--force' => true,
        ]);

        $placeholdersArray = [
            $stubEditor->inputPlaceholders,
            $stubEditor->actionPlaceholders,
            $stubEditor->editUrlPlaceholders,
            $stubEditor->variableCollectionPlaceholders,
            $stubEditor->variablePlaceholders,
            $stubEditor->cancelUrlPlaceholders,
            $stubEditor->modelPlaceholders,
            $stubEditor->vueComponentPlaceholders,
            $stubEditor->vueDataPlaceholders,
            $stubEditor->vuePostDataPlaceholders,
        ];

        // Assert that the expected blade file does not have any stub model placeholders remaining
        foreach ($placeholdersArray as $placeholders) {
            foreach ($placeholders as $placeholder) {
                $this->assertFalse(strpos($expectedBladeFile, $placeholder));
            }
        }
    }

    /**
     * A test for correct default index view file.
     *
     * @return
     */
    public function test_correct_default_index_view_file_created()
    {
        $type = 'index';
        $viewType = 'default';
        $inputs = $this->getMockColumns();
        $expectedStubLocation = base_path() . "/stubs/views/$viewType/$type.stub";
        $stubLocation = dirname(dirname(__DIR__)) . "/Commands/stubs/views/$viewType/$type.stub";
        $stub = File::get($stubLocation);
        $expectedBladeFileLocation = "resources/views/name/$type.blade.php";
        $expectedBladeFile = File::get(dirname(__DIR__) . "/stubs/views/$viewType/expectedBladeFile" . ucfirst($type) . ".stub");

        $inputsLocation = dirname(dirname(__DIR__)) . "/Commands/stubs/views/$viewType/inputs/";

        foreach ($inputs as $input) {
            $input->mockInputStub = File::get($inputsLocation . $this->getInputDefault($input->type) . ".stub");
        }

        // Assert the StubEditor is created correctly.
        $stubEditor = new ViewStub();
        App::shouldReceive('make')
            ->with(StubEditor::class, ['view'])
            ->once()
            ->andReturn($stubEditor);

        // Assert the StubInputsEditor is created correctly.
        App::shouldReceive('make')
            ->with(StubInputsEditor::class, [$inputs, 'view'])
            ->once()
            ->andReturn(new StubInputsEditor($inputs, 'view'));

        // Assert config frontend scaffolding is used.
        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->times(16)
            ->andReturn($viewType);
        
        // Assert config stubs folder is used.
        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->times(4)
            ->andReturn('stubs');
        
        // Assert config input defaults is used.
        Config::shouldReceive('get')
            ->with('cruddy.input_defaults')
            ->times(6)
            ->andReturn($this->getInputDefaults());

        // Assert config input default is used for each input.
        foreach ($inputs as $input) {
            App::shouldReceive('make')
                ->with(StubInputEditor::class, [$input, 'view', '', false])
                ->once()
                ->andReturn(new ViewColumnInputStub($input));
        }

        Config::partialMock();

        // Assert stub at the location exists for file type.
        File::shouldReceive('exists')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn(true);

        // Assert getting the correct stub file for file type.
        File::shouldReceive('get')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn($stub);
        
        foreach ($inputs as $input) {
            $location = base_path() . "/stubs/views/$viewType/inputs/" . $this->getInputDefault($input->type) . ".stub";
            // Assert stub at the location exists.
            File::shouldReceive('exists')
                ->with($location)
                ->once()
                ->andReturn(true);

            // Assert getting the correct stub file for file type.
            File::shouldReceive('get')
                ->with($location)
                ->once()
                ->andReturn($input->mockInputStub);
        }

        // Assert correct blade file is created in the correct location.
        File::shouldReceive('put')
            ->with($expectedBladeFileLocation, $expectedBladeFile)
            ->once()
            ->andReturn(true);

        File::partialMock();

        $this->artisan('cruddy:view', [
            'name' => $this->name,
            'table' => $this->table,
            'type' => $type,
            'inputs' => $inputs,
            '--force' => true,
        ]);

        $placeholdersArray = [
            $stubEditor->inputPlaceholders,
            $stubEditor->actionPlaceholders,
            $stubEditor->editUrlPlaceholders,
            $stubEditor->variableCollectionPlaceholders,
            $stubEditor->variablePlaceholders,
            $stubEditor->cancelUrlPlaceholders,
            $stubEditor->modelPlaceholders,
            $stubEditor->vueComponentPlaceholders,
            $stubEditor->vueDataPlaceholders,
            $stubEditor->vuePostDataPlaceholders,
        ];

        // Assert that the expected blade file does not have any stub model placeholders remaining
        foreach ($placeholdersArray as $placeholders) {
            foreach ($placeholders as $placeholder) {
                $this->assertFalse(strpos($expectedBladeFile, $placeholder));
            }
        }
    }

    /**
     * A test for correct default create view file.
     *
     * @return
     */
    public function test_correct_default_create_view_file_created()
    {
        $type = 'create';
        $viewType = 'default';
        $inputs = $this->getMockColumns();
        $expectedStubLocation = base_path() . "/stubs/views/$viewType/$type.stub";
        $stubLocation = dirname(dirname(__DIR__)) . "/Commands/stubs/views/$viewType/$type.stub";
        $stub = File::get($stubLocation);
        $expectedBladeFileLocation = "resources/views/name/$type.blade.php";
        $expectedBladeFile = File::get(dirname(__DIR__) . "/stubs/views/$viewType/expectedBladeFile" . ucfirst($type) . ".stub");

        $inputsLocation = dirname(dirname(__DIR__)) . "/Commands/stubs/views/$viewType/inputs/";

        foreach ($inputs as $input) {
            $input->mockInputStub = File::get($inputsLocation . $this->getInputDefault($input->type) . ".stub");
        }
        $mockSubmitInputStub = File::get($inputsLocation  . "submit.stub");

        // Assert the StubEditor is created correctly.
        $stubEditor = new ViewStub();
        App::shouldReceive('make')
            ->with(StubEditor::class, ['view'])
            ->once()
            ->andReturn($stubEditor);

        // Assert the StubInputsEditor is created correctly.
        App::shouldReceive('make')
            ->with(StubInputsEditor::class, [$inputs, 'view'])
            ->once()
            ->andReturn(new StubInputsEditor($inputs, 'view'));

        // Assert config frontend scaffolding is used.
        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->times(18)
            ->andReturn($viewType);
        
        // Assert config stubs folder is used.
        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->times(5)
            ->andReturn('stubs');
        
        // Assert config input defaults is used.
        Config::shouldReceive('get')
            ->with('cruddy.input_defaults')
            ->times(8)
            ->andReturn($this->getInputDefaults());

        // Assert config input default is used for each input.
        foreach ($inputs as $input) {
            App::shouldReceive('make')
                ->with(StubInputEditor::class, [$input, 'view', '', true])
                ->once()
                ->andReturn(new ViewColumnInputStub($input));
        }

        // Assertion for submit input
        App::shouldReceive('make')
            ->with(StubInputEditor::class, [null, 'view', '', true])
            ->once()
            ->andReturn(new ViewColumnInputStub());

        Config::partialMock();

        // Assert stub at the location exists for file type.
        File::shouldReceive('exists')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn(true);

        // Assert getting the correct stub file for file type.
        File::shouldReceive('get')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn($stub);
        
        foreach ($inputs as $input) {
            $location = base_path() . "/stubs/views/$viewType/inputs/" . $this->getInputDefault($input->type) . ".stub";
            // Assert stub at the location exists.
            File::shouldReceive('exists')
                ->with($location)
                ->once()
                ->andReturn(true);

            // Assert getting the correct stub file for file type.
            File::shouldReceive('get')
                ->with($location)
                ->once()
                ->andReturn($input->mockInputStub);
        }

        // Assert file uses submit input stub
        $location = base_path() . "/stubs/views/$viewType/inputs/submit.stub";
        File::shouldReceive('exists')
            ->with($location)
            ->once()
            ->andReturn(true);
    
        File::shouldReceive('get')
            ->with($location)
            ->once()
            ->andReturn($mockSubmitInputStub);

        // Assert correct blade file is created in the correct location.
        File::shouldReceive('put')
            ->with($expectedBladeFileLocation, $expectedBladeFile)
            ->once()
            ->andReturn(true);

        File::partialMock();

        $this->artisan('cruddy:view', [
            'name' => $this->name,
            'table' => $this->table,
            'type' => $type,
            'inputs' => $inputs,
            '--force' => true,
        ]);

        $placeholdersArray = [
            $stubEditor->inputPlaceholders,
            $stubEditor->actionPlaceholders,
            $stubEditor->editUrlPlaceholders,
            $stubEditor->variableCollectionPlaceholders,
            $stubEditor->variablePlaceholders,
            $stubEditor->cancelUrlPlaceholders,
            $stubEditor->modelPlaceholders,
            $stubEditor->vueComponentPlaceholders,
            $stubEditor->vueDataPlaceholders,
            $stubEditor->vuePostDataPlaceholders,
        ];

        // Assert that the expected blade file does not have any stub model placeholders remaining
        foreach ($placeholdersArray as $placeholders) {
            foreach ($placeholders as $placeholder) {
                $this->assertFalse(strpos($expectedBladeFile, $placeholder));
            }
        }
    }

    /**
     * A test for correct default show view file.
     *
     * @return
     */
    public function test_correct_default_show_view_file_created()
    {
        $type = 'show';
        $viewType = 'default';
        $inputs = $this->getMockColumns();
        $expectedStubLocation = base_path() . "/stubs/views/$viewType/$type.stub";
        $stubLocation = dirname(dirname(__DIR__)) . "/Commands/stubs/views/$viewType/$type.stub";
        $stub = File::get($stubLocation);
        $expectedBladeFileLocation = "resources/views/name/$type.blade.php";
        $expectedBladeFile = File::get(dirname(__DIR__) . "/stubs/views/$viewType/expectedBladeFile" . ucfirst($type) . ".stub");

        $inputsLocation = dirname(dirname(__DIR__)) . "/Commands/stubs/views/$viewType/inputs/";

        foreach ($inputs as $input) {
            $input->mockInputStub = File::get($inputsLocation . $this->getInputDefault($input->type) . ".stub");
        }

        // Assert the StubEditor is created correctly.
        $stubEditor = new ViewStub();
        App::shouldReceive('make')
            ->with(StubEditor::class, ['view'])
            ->once()
            ->andReturn($stubEditor);

        // Assert the StubInputsEditor is created correctly.
        App::shouldReceive('make')
            ->with(StubInputsEditor::class, [$inputs, 'view'])
            ->once()
            ->andReturn(new StubInputsEditor($inputs, 'view'));

        // Assert config frontend scaffolding is used.
        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->times(15)
            ->andReturn($viewType);
        
        // Assert config stubs folder is used.
        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->times(4)
            ->andReturn('stubs');
        
        // Assert config input defaults is used.
        Config::shouldReceive('get')
            ->with('cruddy.input_defaults')
            ->times(6)
            ->andReturn($this->getInputDefaults());

        // Assert config input default is used for each input.
        foreach ($inputs as $input) {
            App::shouldReceive('make')
                ->with(StubInputEditor::class, [$input, 'view', '', false])
                ->once()
                ->andReturn(new ViewColumnInputStub($input));
        }

        Config::partialMock();

        // Assert stub at the location exists for file type.
        File::shouldReceive('exists')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn(true);

        // Assert getting the correct stub file for file type.
        File::shouldReceive('get')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn($stub);
        
        foreach ($inputs as $input) {
            $location = base_path() . "/stubs/views/$viewType/inputs/" . $this->getInputDefault($input->type) . ".stub";
            // Assert stub at the location exists.
            File::shouldReceive('exists')
                ->with($location)
                ->once()
                ->andReturn(true);

            // Assert getting the correct stub file for file type.
            File::shouldReceive('get')
                ->with($location)
                ->once()
                ->andReturn($input->mockInputStub);
        }

        // Assert correct blade file is created in the correct location.
        File::shouldReceive('put')
            ->with($expectedBladeFileLocation, $expectedBladeFile)
            ->once()
            ->andReturn(true);

        File::partialMock();

        $this->artisan('cruddy:view', [
            'name' => $this->name,
            'table' => $this->table,
            'type' => $type,
            'inputs' => $inputs,
            '--force' => true,
        ]);

        $placeholdersArray = [
            $stubEditor->inputPlaceholders,
            $stubEditor->actionPlaceholders,
            $stubEditor->editUrlPlaceholders,
            $stubEditor->variableCollectionPlaceholders,
            $stubEditor->variablePlaceholders,
            $stubEditor->cancelUrlPlaceholders,
            $stubEditor->modelPlaceholders,
            $stubEditor->vueComponentPlaceholders,
            $stubEditor->vueDataPlaceholders,
            $stubEditor->vuePostDataPlaceholders,
        ];

        // Assert that the expected blade file does not have any stub model placeholders remaining
        foreach ($placeholdersArray as $placeholders) {
            foreach ($placeholders as $placeholder) {
                $this->assertFalse(strpos($expectedBladeFile, $placeholder));
            }
        }
    }

    /**
     * A test for correct default edit view file.
     *
     * @return
     */
    public function test_correct_default_edit_view_file_created()
    {
        $type = 'edit';
        $viewType = 'default';
        $inputs = $this->getMockColumns();
        $expectedStubLocation = base_path() . "/stubs/views/$viewType/$type.stub";
        $stubLocation = dirname(dirname(__DIR__)) . "/Commands/stubs/views/$viewType/$type.stub";
        $stub = File::get($stubLocation);
        $expectedBladeFileLocation = "resources/views/name/$type.blade.php";
        $expectedBladeFile = File::get(dirname(__DIR__) . "/stubs/views/$viewType/expectedBladeFile" . ucfirst($type) . ".stub");

        $inputsLocation = dirname(dirname(__DIR__)) . "/Commands/stubs/views/$viewType/inputs/";

        foreach ($inputs as $input) {
            $input->mockInputStub = File::get($inputsLocation . $this->getInputDefault($input->type) . ".stub");
        }
        $mockSubmitInputStub = File::get($inputsLocation  . "submit.stub");

        // Assert the StubEditor is created correctly.
        $stubEditor = new ViewStub();
        App::shouldReceive('make')
            ->with(StubEditor::class, ['view'])
            ->once()
            ->andReturn($stubEditor);

        // Assert the StubInputsEditor is created correctly.
        App::shouldReceive('make')
            ->with(StubInputsEditor::class, [$inputs, 'view'])
            ->once()
            ->andReturn(new StubInputsEditor($inputs, 'view'));

        // Assert config frontend scaffolding is used.
        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->times(19)
            ->andReturn($viewType);
        
        // Assert config stubs folder is used.
        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->times(5)
            ->andReturn('stubs');
        
        // Assert config input defaults is used.
        Config::shouldReceive('get')
            ->with('cruddy.input_defaults')
            ->times(8)
            ->andReturn($this->getInputDefaults());

        // Assert config input default is used for each input.
        foreach ($inputs as $input) {
            App::shouldReceive('make')
                ->with(StubInputEditor::class, [$input, 'view', '', true])
                ->once()
                ->andReturn(new ViewColumnInputStub($input));
        }

        // Assertion for submit input
        App::shouldReceive('make')
            ->with(StubInputEditor::class, [null, 'view', '', true])
            ->once()
            ->andReturn(new ViewColumnInputStub());

        Config::partialMock();

        // Assert stub at the location exists for file type.
        File::shouldReceive('exists')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn(true);

        // Assert getting the correct stub file for file type.
        File::shouldReceive('get')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn($stub);
        
        foreach ($inputs as $input) {
            $location = base_path() . "/stubs/views/$viewType/inputs/" . $this->getInputDefault($input->type) . ".stub";
            // Assert stub at the location exists.
            File::shouldReceive('exists')
                ->with($location)
                ->once()
                ->andReturn(true);

            // Assert getting the correct stub file for file type.
            File::shouldReceive('get')
                ->with($location)
                ->once()
                ->andReturn($input->mockInputStub);
        }

        // Assert file uses submit input stub
        $location = base_path() . "/stubs/views/$viewType/inputs/submit.stub";
        File::shouldReceive('exists')
            ->with($location)
            ->once()
            ->andReturn(true);
    
        File::shouldReceive('get')
            ->with($location)
            ->once()
            ->andReturn($mockSubmitInputStub);

        // Assert correct blade file is created in the correct location.
        File::shouldReceive('put')
            ->with($expectedBladeFileLocation, $expectedBladeFile)
            ->once()
            ->andReturn(true);

        File::partialMock();

        $this->artisan('cruddy:view', [
            'name' => $this->name,
            'table' => $this->table,
            'type' => $type,
            'inputs' => $inputs,
            '--force' => true,
        ]);

        $placeholdersArray = [
            $stubEditor->inputPlaceholders,
            $stubEditor->actionPlaceholders,
            $stubEditor->editUrlPlaceholders,
            $stubEditor->variableCollectionPlaceholders,
            $stubEditor->variablePlaceholders,
            $stubEditor->cancelUrlPlaceholders,
            $stubEditor->modelPlaceholders,
            $stubEditor->vueComponentPlaceholders,
            $stubEditor->vueDataPlaceholders,
            $stubEditor->vuePostDataPlaceholders,
        ];

        // Assert that the expected blade file does not have any stub model placeholders remaining
        foreach ($placeholdersArray as $placeholders) {
            foreach ($placeholders as $placeholder) {
                $this->assertFalse(strpos($expectedBladeFile, $placeholder));
            }
        }
    }
}