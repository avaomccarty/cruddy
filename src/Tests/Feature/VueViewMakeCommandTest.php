<?php

namespace Cruddy\Tests\Feature;

use Cruddy\Tests\TestTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;

class VueViewMakeCommandTest extends TestCase
{
    use TestTrait;

    /**
     * A test for the index type.
     *
     * @return void
     */
    public function test_index_type()
    {
        $name = 'Foo';
        $type = 'index';
        $stub = 'stub';
        $updatedStub = 'updatedStub';
        $vueImportFile = 'resources/js/bootstrap.js';
        $searchString = 'searchString';
        $importStatement = "import FooControllerIndex from '@/components/foocontroller/index.vue';\n";
        $componentStatement = "Vue.component('foo-controller-index', FooControllerIndex);\nsearchString";

        Config::shouldReceive('get')
            ->with('cruddy.vue_import_file')
            ->times(4)
            ->andReturn($vueImportFile);

        Config::shouldReceive('get')
            ->with('cruddy.vue_search_string')
            ->once()
            ->andReturn($searchString);

        Config::partialMock();

        File::shouldReceive('exists')
            ->with($vueImportFile)
            ->once()
            ->andReturn(true);

        File::shouldReceive('get')
            ->with($vueImportFile)
            ->once()
            ->andReturn($searchString);

        File::shouldReceive('prepend')
            ->with($vueImportFile, $importStatement)
            ->once()
            ->andReturn($stub);

        File::shouldReceive('put')
            ->with($vueImportFile, $componentStatement)
            ->once()
            ->andReturn($updatedStub);

        File::partialMock();

        $this->artisan('cruddy:vue-view', [
            'name' => $name . 'Controller',
            'type' => $type,
        ]);
    }
}