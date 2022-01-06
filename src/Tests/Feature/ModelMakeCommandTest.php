<?php

namespace Cruddy\Tests\Feature;

use Cruddy\Exceptions\UnknownRelationshipType;
use Cruddy\ForeignKeyDefinition;
use Cruddy\ModelRelationships\ModelRelationship;
use Cruddy\StubEditors\ModelStubEditor;
use Cruddy\StubEditors\StubEditor;
use Cruddy\Tests\TestTrait;
use Cruddy\Traits\CommandTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;

class ModelMakeCommandTest extends TestCase
{
    use TestTrait, CommandTrait;

    /**
     * A test to create a model file.
     *
     * @return void
     */
    public function test_create_model()
    {
        $name = 'name';
        $type = 'model';
        $inputs = $this->getMockColumns();
        $keys = $this->getMockCommands();

        $expectedStubLocation = base_path() . '/stubs/model.stub';
        $stubLocation = dirname(dirname(__DIR__)) . '/Commands/stubs/model.stub';
        $stub = File::get($stubLocation);
        $expectedBladeFileLocation = base_path() . '/app/Models';
        $expectedBladeFile = File::get(dirname(__DIR__) . '/stubs/models/expectedFile.stub');
        $expectedBladeFileName = $expectedBladeFileLocation . '/name.php';

        $stubeditor = new ModelStubEditor();
        App::shouldReceive('make')
            ->with(StubEditor::class, [$type])
            ->once()
            ->andReturn($stubeditor);

        foreach ($keys as $key) {
            $modelRelationship = new ModelRelationship($key);
            App::shouldReceive('make')
                ->with(ModelRelationship::class, [$key])
                ->once()
                ->andReturn($modelRelationship);
        }

        App::partialMock();

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->once()
            ->andReturn('stubs');

        Config::partialMock();

        File::shouldReceive('exists')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn(true);

        File::shouldReceive('get')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn($stub);

        File::shouldReceive('isDirectory')
            ->with($expectedBladeFileLocation)
            ->once()
            ->andReturn(true);

        File::shouldReceive('put')
            ->with($expectedBladeFileName, $expectedBladeFile)
            ->once()
            ->andReturn(true);

        File::partialMock();

        $this->artisan('cruddy:model', [
            'name' => $name,
            'inputs' => $inputs,
            'keys' => $keys,
            '--force' => true,
        ])->expectsOutput('Model created successfully.');
    }

    /**
     * A test for an invalid relationship type.
     *
     * @return void
     */
    public function test_invalid_relationship_type()
    {
        $this->expectException(UnknownRelationshipType::class);

        $foreignKey = new ForeignKeyDefinition([
            'relationship' => 'invalid-type',
            'on' => 'on'
        ]);

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->once()
            ->andReturn('stubs');

        Config::partialMock();

        $this->artisan('cruddy:model', [
            'name' => 'name',
            'inputs' => $this->getMockColumns(),
            'keys' => [$foreignKey],
            '--force' => true,
        ]);
    }
}