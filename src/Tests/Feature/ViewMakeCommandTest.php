<?php

namespace Cruddy\Tests\Feature;

use Cruddy\ServiceProvider;
use Cruddy\Tests\TestTrait;
use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\Stubs\InputTrait;
use Cruddy\Traits\Stubs\ModelTrait;
use Cruddy\Traits\ViewMakeCommandTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;

class ViewMakeCommandTest extends TestCase
{
    use ViewMakeCommandTrait, TestTrait, InputTrait, ModelTrait, CommandTrait;

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
     * Get the expected location for the input stub.
     *
     * @param  string  $input
     * @return string
     */
    protected function getExpectedInputStubLocation(string $input = 'text') : string
    {
        return base_path() . '/stubs/views/default/inputs/' . $input . '.stub';
    }

    /**
     * Get the location for the test input stub.
     *
     * @param  string  $input
     * @return string
     */
    protected function getInputStubMock(string $input = 'text') : string
    {
        $location = dirname(dirname(__DIR__)) . '/Commands/stubs/views/default/inputs/' . $input . '.stub';
        return File::get($location);
    }

    /**
     * Get the blade stub location.
     *
     * @param  string  $type
     * @return string
     */
    protected function getBladeLocation(string $type = 'index') : string
    {
        return dirname(__DIR__) . '/stubs/views/expectedBladeFile' . ucfirst($type) . '.stub';
    }

    /**
     * Get the expected blade file location.
     *
     * @param  string  $type
     * @return string
     */
    protected function getExpectedBladeFileLocation(string $type = 'index') : string
    {
        return 'resources/views/name/' . $type . '.blade.php';
    }

    /**
     * Get the expected final blade file with correct values.
     *
     * @param  string  $type
     * @return string
     */
    protected function getExpectedBladeFile(string $type = 'index') : string
    {
        return File::get($this->getBladeLocation($type));
    }

    /**
     * Get the expected number of calls to the config for the frontend scaffolding.
     *
     * @param  string  $type
     * @param  integer  $inputsCount
     * @return integer
     */
    protected function getExpectedFrontendScaffoldingConfigCalls(string $type = 'index', int $inputsCount = 0) : int
    {
        switch ($type) {
            case 'edit':
                return 13 + $inputsCount;
                break;
            case 'show':
                return 11 + $inputsCount;
                break;
            default:
                return 8 + $inputsCount;
                break;
        }
    }

    /**
     * Get the number of calls to the config stubs folder.
     *
     * @param  string  $type
     * @param  integer  $inputsCount
     * @return integer
     */
    protected function getStubsFolderConfigCalls(string $type = 'index', int $inputsCount = 0) : int
    {
        $count = $inputsCount + 1;
        if ($type !== 'index' && $type !== 'show') {
            $count += 1;
        }

        return $count;
    }

    /**
     * Get the number of calls to the config stubs folder.
     *
     * @param  string  $type
     * @param  integer  $inputsCount
     * @return integer
     */
    protected function getInputDefaultsConfigCalls(string $type = 'index', int $inputsCount = 0) : int
    {
        $count = $inputsCount;
        if ($type !== 'index' && $type !== 'show') {
            $count += 1;
        }

        return $count;
    }

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
     * @return string
     */
    protected function getInputDefault(string $input = 'string') : string
    {
        return $this->getInputDefaults()[$input];
    }

    /**
     * Get the assertions based on the type of request file being created.
     *
     * @param  string  $type
     * @return
     */
    public function getAssertionsByType(string $type)
    {
        $inputs = $this->getMockColumns();
        $inputsCount = count($inputs);
        $expectedStubLocation = $this->getExpectedTypeStubLocation($type);
        $stubLocation = $this->getTypeStubLocation($type);
        $stub = File::get($stubLocation);
        $expectedBladeFileLocation = $this->getExpectedBladeFileLocation($type);
        $expectedBladeFile = $this->getExpectedBladeFile($type);

        foreach ($inputs as $input) {
            $input->mockInputStub = $this->getInputStubMock($input->inputType);
        }
        $mockSubmitInputStub = $this->getInputStubMock('submit');

        // Assert config frontend scaffolding is used.
        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->times($this->getExpectedFrontendScaffoldingConfigCalls($type, $inputsCount))
            ->andReturn('default');
        
        // Assert config stubs folder is used.
        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->times($this->getStubsFolderConfigCalls($type, $inputsCount))
            ->andReturn('stubs');
        
        // Assert config input defaults is used.
        Config::shouldReceive('get')
            ->with('cruddy.input_defaults')
            ->times($this->getInputDefaultsConfigCalls($type, $inputsCount))
            ->andReturn($this->getInputDefaults());

        // Assert config input default is used for each input.
        foreach ($inputs as $input) {
            Config::shouldReceive('get')
                ->with('cruddy.input_defaults.' . $input->type)
                ->once()
                ->andReturn($this->getInputDefault($input->type));
        }

        // Assert 'edit' and 'create' files use config input_defaults submit button
        if ($type !== 'index' && $type !== 'show') {
            Config::shouldReceive('get')
                ->with('cruddy.input_defaults.submit')
                ->once()
                ->andReturn($this->getInputDefault('submit'));
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
            // Assert stub at the location exists.
            File::shouldReceive('exists')
                ->with($this->getExpectedInputStubLocation($input->inputType))
                ->once()
                ->andReturn(true);

            // Assert getting the correct stub file for file type.
            File::shouldReceive('get')
                ->with($this->getExpectedInputStubLocation($input->inputType))
                ->once()
                ->andReturn($input->mockInputStub);
        }

        // Assert 'edit' and 'create' files use submit input stub
        if ($type !== 'index' && $type !== 'show') {
            File::shouldReceive('exists')
                ->with($this->getExpectedInputStubLocation('submit'))
                ->once()
                ->andReturn(true);
        
            File::shouldReceive('get')
                ->with($this->getExpectedInputStubLocation('submit'))
                ->once()
                ->andReturn($mockSubmitInputStub);
        }

        // Assert stub at the location exists.
        File::shouldReceive('exists')
            ->with($expectedBladeFileLocation)
            ->once()
            ->andReturn(false);

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
            'inputs' => $inputs
        ])->expectsOutput('Cruddy view created successfully.');

        // Assert that the expected blade file does not have any stub model placeholders remaining
        foreach ($this->stubModelPlaceholders as $stubModelPlaceholder) {
            $this->assertFalse(strpos($expectedBladeFile, $stubModelPlaceholder));
        }

        // Assert that the expected blade file does not have any stub input placeholders remaining
        foreach ($this->stubInputPlaceholders as $stubInputPlaceholder) {
            $this->assertFalse(strpos($expectedBladeFile, $stubInputPlaceholder));
        }
    }

    /**
     * A test for correct index view file.
     *
     * @return
     */
    public function test_correct_index_view_file_created()
    {
        $this->getAssertionsByType('index');
    }
    /**
     * A test for correct create view file.
     *
     * @return
     */
    public function test_correct_create_view_file_created()
    {
        $this->getAssertionsByType('create');
    }
    /**
     * A test for correct show view file.
     *
     * @return
     */
    public function test_correct_show_view_file_created()
    {
        $this->getAssertionsByType('show');
    }
    /**
     * A test for correct edit view file.
     *
     * @return
     */
    public function test_correct_edit_view_file_created()
    {
        $this->getAssertionsByType('edit');
    }
}