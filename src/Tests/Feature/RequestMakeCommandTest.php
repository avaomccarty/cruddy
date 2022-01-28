<?php

namespace Cruddy\Tests\Feature;

use Cruddy\ForeignKeyDefinition;
use Cruddy\StubEditors\Validation\ForeignKeyValidationStub;
use Cruddy\StubEditors\Inputs\Input\Columns\RequestColumnInputStub;
use Cruddy\StubEditors\Inputs\Input\InputStub;
use Cruddy\StubEditors\Inputs\StubInputsEditor;
use Cruddy\StubEditors\RequestStub;
use Cruddy\StubEditors\StubEditor;
use Cruddy\Tests\TestTrait;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;

class RequestMakeCommandTest extends TestCase
{
    use TestTrait;

    /**
     * The output from successfully running the command.
     *
     * @var string
     */
    protected $successOutput = 'Cruddy request created successfully.';

    /**
     * The name of the resource.
     *
     * @var string
     */
    protected $name = 'test';

    /**
     * The location of the stubs folder.
     *
     * @var string
     */
    protected $stubPath = '/stubs';

    /**
     * The validation defaults for the tests.
     *
     * @var array
     */
    protected $validationDefaults = [
        'oneToOne' => '',
        'bigInteger' => 'required|integer|min:1',
        'integer' => 'integer',
        'string' => 'nullable|string',
    ];

    /**
     * Get the assertions based on the type of request file being created.
     *
     * @param  string  $type
     * @return 
     */
    public function getAssertionsByType(string $type)
    {
        $rules = $this->getMockRules();
        $rulesPlaceholders = [
            'DummyRules',
            '{{ rules }}',
            '{{rules}}'
        ];
        $fileType = 'request';
        $requestFileName = ucfirst($type) . ucfirst($this->name);
        $stubPath = 'stubs';
        $stubLocation = $this->getStubLocation($fileType);
        $stub = File::get($stubLocation);

        $expectedRequestFileLocation = $this->getExpectedRequestFileLocation($requestFileName);
        $expectedRequestFile = $this->getExpectedRequestFile($type);

        // Assert the StubEditor is created correctly.
        $stubEditor = new RequestStub();
        App::shouldReceive('make')
            ->with(StubEditor::class, ['request'])
            ->once()
            ->andReturn($stubEditor);

        // Assert the StubInputsEditor is created correctly.
        App::shouldReceive('make')
            ->with(StubInputsEditor::class, [$rules, 'request'])
            ->once()
            ->andReturn(new StubInputsEditor($rules, 'request'));

        File::shouldReceive('exists')
            ->with($expectedRequestFileLocation)
            ->once()
            ->andReturn(false);

        File::shouldReceive('get')
            ->with($stubLocation)
            ->once()
            ->andReturn($stub);

        File::shouldReceive('put')
            ->with($expectedRequestFileLocation, $expectedRequestFile)
            ->once();

        File::partialMock();

        $columnCount = 0;
        foreach ($rules as $rule) {
            if (is_a($rule, ColumnDefinition::class)) {
                App::shouldReceive('make')
                    ->with(StubInputEditor::class, [$rule, 'request', '', false])
                    ->once()
                    ->andReturn(new RequestColumnInputStub($rule));
                $columnCount++;
            }
            if (is_a($rule, ForeignKeyDefinition::class)) {
                App::shouldReceive('make')
                    ->with(ForeignKeyValidationStub::class, [$rule])
                    ->once()
                    ->andReturn(new ForeignKeyValidationStub($rule));
            }
        }

        Config::shouldReceive('get')
            ->with('cruddy.validation_defaults')
            ->times($columnCount)
            ->andReturn($this->validationDefaults);

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->once()
            ->andReturn($stubPath);

        Config::partialMock();

        $this->artisan('cruddy:request', [
            'name' => $this->name,
            'type' => $type,
            'rules' => $rules,
        ])
            ->expectsOutput($this->successOutput)
            ->assertExitCode(0);

        // Assert that the expected request file does not have any stub rule placeholders remaining
        foreach ($rulesPlaceholders as $placeholder) {
            $this->assertFalse(strpos($expectedRequestFile, $placeholder));
        }
    }

    /**
     * A test for correct update request file.
     *
     * @test
     * @group cruddy
     * @return void
     */
    public function test_correct_update_request_file_created()
    {
        $this->getAssertionsByType('update');
    }

    /**
     * A test for correct store request file.
     *
     * @test
     * @group cruddy
     * @return void
     */
    public function test_correct_store_request_file_created()
    {
        $this->getAssertionsByType('store');
    }

}