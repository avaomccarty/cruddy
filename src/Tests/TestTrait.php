<?php

namespace Cruddy\Tests;

use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\File;
use Cruddy\ServiceProvider;
use Cruddy\ForeignKeyDefinition;

trait TestTrait
{
    /**
     * The connection for the database.
     *
     * @var string
     */
    protected $dbConnection = [];

    /**
     * The name for the string column.
     *
     * @var string
     */
    protected $nameString = 'name-string';

    /**
     * The name for the integer column.
     *
     * @var string
     */
    protected $nameInteger = 'name-integer';

    /**
     * The name for the bigInteger column.
     *
     * @var string
     */
    protected $nameBigInteger = 'name-bigInteger';

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
     * This method is needed on the class and should not actually do anything.
     * Must have the same number of parameters as original "option" method 
     * and must return a value for mocks.
     * 
     * @return mixed
     */
    public function replaceClass($x, $y) : mixed
    {
        // Need to return a value for mocking.
        return $this;
    }

    /**
     * This method is needed on the class and should not actually do anything.
     * Must have the same number of parameters as original "argument" method 
     * and must return a value for mocks.
     * 
     * @return mixed
     */
    public function argument($x) : mixed
    {
        // Need to return a value for mocking.
        return $x;
    }

    /**
     * This method is needed on the class and should not actually do anything.
     * Must have the same number of parameters as original "option" method 
     * and must return a value for mocks.
     * 
     * @return mixed
     */
    public function option($x) : mixed
    {
        if ($x == 'inputs') {
            // Return a valid array of inputs
            return $this->inputs;
        }

        if ($x == 'api') {
            // Return a valid boolean value
            return $this->isApi ?? false;
        }

        // Need to return a value for mocking.
        return $x;
    }

    /**
     * Get an array of all the acceptable types of rules.
     *
     * @return array
     */
    public function getMockRules() : array
    {
        return array_merge($this->getMockColumns(), $this->getMockCommands());
    }

    /**
     * Get an array of all the acceptable rules in an acceptable format.
     *
     * @return array
     */
    public function getMockColumns() : array
    {
        return [
            new ColumnDefinition([
                'type' => 'string',
                'name' => $this->nameString,
                'length' => 200,
                'relationship' => 'text',
            ]),
            new ColumnDefinition([
                'type' => 'integer',
                'name' => $this->nameInteger,
                'min' => 1,
                'max' => 1000,
                'relationship' => 'number',
            ]),
            new ColumnDefinition([
                'type' => 'bigInteger',
                'name' => $this->nameBigInteger,
                'min' => 1,
                'max' => 9999,
                'relationship' => 'number',
            ]),
        ];
    }

    /**
     * Get an array of all the acceptable commands in an acceptable format.
     *
     * @return array
     */
    public function getMockCommands() : array
    {
        return [
            new ForeignKeyDefinition([
                'name' => 'foreign',
                'index' => 'foos_user_id_foreign',
                'columns' => [
                    0 => $this->nameBigInteger,
                ],
                'algorithm' => null,
                'references' => 'id',
                'on' => 'users',
                'relationship' => 'hasOne',
            ]),
            new ForeignKeyDefinition([
                'name' => 'foreign',
                'index' => 'foos_user_id_foreign',
                'columns' => [
                    0 => $this->nameBigInteger,
                ],
                'algorithm' => null,
                'references' => 'id',
                'on' => 'users',
                'relationship' => 'default',
            ]),
        ];
    }

    /**
     * Get the location for the test stub.
     *
     * @param  string  $type
     * @return string
     */
    protected function getStubLocation(string $type = 'controller') : string
    {
        return dirname(__DIR__) . '/Commands/stubs/' . $type . '.stub';
    }

    /**
     * Get the expected request file location.
     *
     * @param  string  $name
     * @return string
     */
    protected function getExpectedRequestFileLocation(string $name) : string
    {
        return app_path() . '/Http/Requests/' . $name . '.php';
    }

    /**
     * Get the request stub location.
     *
     * @param  string  $type
     * @return string
     */
    protected function getRequestLocation(string $type = 'update') : string
    {
        return __DIR__ . '/stubs/requests/expectedRequestFile' . ucfirst($type) . '.stub';
    }

    /**
     * Get the expected final request file with correct values.
     *
     * @param  string  $type
     * @return string
     */
    protected function getExpectedRequestFile(string $type = 'update') : string
    {
        return File::get($this->getRequestLocation($type));
    }

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // /**
    //  * Get the expected location for the input stub.
    //  *
    //  * @param  string  $input = 'text'
    //  * @param  string  $type = 'default'
    //  * @return string
    //  */
    // protected function getExpectedInputStubLocation(string $input = 'text', $type = 'default') : string
    // {
    //     return base_path() . "/stubs/views/$type/inputs/$input.stub";
    // }

    // /**
    //  * Get the location for the test input stub.
    //  *
    //  * @param  string  $input = 'text'
    //  * @param  string  $type = 'default'
    //  * @return string
    //  */
    // protected function getInputStubMock(string $input = 'text', $type = 'default') : string
    // {
    //     $location = dirname(__DIR__) . "/Commands/stubs/views/$type/inputs/$input.stub";
    //     return File::get($location);
    // }

    // /**
    //  * Get the blade stub location.
    //  *
    //  * @param  string  $input = 'text'
    //  * @param  string  $type = 'default'
    //  * @return string
    //  */
    // protected function getBladeLocation(string $input = 'index', $type = 'default') : string
    // {
    //     return __DIR__ . '/stubs/views/' . $type . '/expectedBladeFile' . ucfirst($input) . '.stub';
    // }

    // /**
    //  * Get the expected blade file location.
    //  *
    //  * @param  string  $input = 'text'
    //  * @param  string  $type = 'default'
    //  * @return string
    //  */
    // protected function getExpectedBladeFileLocation(string $input = 'index', string $viewType = 'default') : string
    // {
    //     if ($viewType === 'vue') {
    //         return "stubs/Table/$input.vue";
    //     }

    //     return 'resources/views/name/' . $input . '.blade.php';
    // }

    // /**
    //  * Get the expected final blade file with correct values.
    //  *
    //  * @param  string  $input = 'text'
    //  * @param  string  $type = 'default'
    //  * @return string
    //  */
    // protected function getExpectedBladeFile(string $input = 'index', $type = 'default') : string
    // {
    //     return File::get($this->getBladeLocation($input, $type));
    // }
}