<?php

namespace Cruddy\Tests\Feature;

use Cruddy\ServiceProvider;
use Cruddy\Tests\TestTrait;
use Cruddy\Traits\Stubs\InputTrait;
use Cruddy\Traits\Stubs\ModelTrait;
use Cruddy\Traits\ViewMakeCommandTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;

class ViewMakeCommandTest extends TestCase
{
    use ViewMakeCommandTrait, TestTrait, InputTrait, ModelTrait;

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
     * Whether to load the environment variables for the tests.
     *
     * @var boolean
     */
    protected $loadEnvironmentVariables = true;

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    /**
     * Get the expected location for the stub.
     *
     * @param  string  $type
     * @return string
     */
    protected function getExpectedTypeStubLocation(string $type = 'index') : string
    {
        return base_path() . '/stubs/views/default/' . $type . '.stub';
    }

    /**
     * Get the location for the test stub.
     *
     * @param  string  $type
     * @return string
     */
    protected function getTypeStubLocation(string $type = 'index') : string
    {
        return dirname(dirname(__DIR__)) . '/Commands/stubs/views/default/' . $type . '.stub';
    }

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
    protected function getInputStubLocation(string $input = 'index') : string
    {
        return dirname(dirname(__DIR__)) . '/Commands/stubs/views/default/inputs/' . $input . '.stub';
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
        $location = dirname(__DIR__) . '/stubs/views/expectedBladeFile' . ucfirst($type) . '.stub';
        return File::get($location);
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
                return 10 + $inputsCount;
                break;
            case 'show':
                return 9 + $inputsCount;
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

    // /**
    //  * Get the number of calls to the config stubs folder.
    //  *
    //  * @param  string  $type
    //  * @param  integer  $inputsCount
    //  * @return integer
    //  */
    // protected function getInputFileCalls(string $type = 'index', int $inputsCount = 0) : int
    // {
    //     $count = $inputsCount;
    //     if ($type !== 'index' && $type !== 'show') {
    //         $count += 1;
    //     }

    //     return $count;
    // }

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
     * A test for the command with all the defaults.
     *
     * @return 
     */
    public function test_all_file_types_with_defaults()
    {
        $inputs = $this->getMockColumns();
        $inputsCount = count($inputs);
        $types = $this->getDefaultTypes();
        $types = [
            'index',
            'create',
        ]; // Note: Need to remove this array at some point.
        foreach ($types as $type) {
            $expectedStubLocation = $this->getExpectedTypeStubLocation($type);
            $stubLocation = $this->getTypeStubLocation($type);
            $stub = File::get($stubLocation);
            $expectedBladeFileLocation = $this->getExpectedBladeFileLocation($type);
            $expectedBladeFile = $this->getExpectedBladeFile($type);

            Config::shouldReceive('get')
                ->with('cruddy.frontend_scaffolding')
                ->times($this->getExpectedFrontendScaffoldingConfigCalls($type, $inputsCount))
                ->andReturn('default');
            
            Config::shouldReceive('get')
                ->with('cruddy.stubs_folder')
                ->times($this->getStubsFolderConfigCalls($type, $inputsCount))
                ->andReturn('stubs');
            
            Config::shouldReceive('get')
                ->with('cruddy.input_defaults')
                ->times($this->getInputDefaultsConfigCalls($type, $inputsCount))
                ->andReturn($this->getInputDefaults());
            
            foreach ($inputs as $input) {
                Config::shouldReceive('get')
                    ->with('cruddy.input_defaults.' . $input->type)
                    ->once()
                    ->andReturn($this->getInputDefault($input->type));
            }

            if ($type !== 'index' && $type !== 'show') {
                Config::shouldReceive('get')
                    ->with('cruddy.input_defaults.submit')
                    ->once()
                    ->andReturn($this->getInputDefault('submit'));
            }

            Config::partialMock();

            File::shouldReceive('exists')
                ->with($expectedStubLocation)
                ->once()
                ->andReturn(true);
            
            File::shouldReceive('get')
                ->with($expectedStubLocation)
                ->once()
                ->andReturn($stub);
            
            foreach ($inputs as $input) {
                File::shouldReceive('exists')
                    ->with($this->getExpectedInputStubLocation($input->inputType))
                    ->once()
                    ->andReturn($this->getInputStubLocation($input->inputType));
                
                File::shouldReceive('get')
                    ->with($this->getExpectedInputStubLocation($input->inputType))
                    ->once()
                    ->andReturn($this->getInputStubLocation($input->inputType));
            }

            if ($type !== 'index' && $type !== 'show') {
                File::shouldReceive('exists')
                    ->with($this->getExpectedInputStubLocation('submit'))
                    ->once()
                    ->andReturn($this->getInputStubLocation('submit'));
            
                File::shouldReceive('get')
                    ->with($this->getExpectedInputStubLocation('submit'))
                    ->once()
                    ->andReturn($this->getInputStubLocation('submit'));
            }
            File::shouldReceive('exists')
                ->with($expectedBladeFileLocation)
                ->once()
                ->andReturn(false);

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
    }
}