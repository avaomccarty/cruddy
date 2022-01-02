<?php

namespace Cruddy\Tests\Unit\StubEditors;

use Cruddy\StubEditors\ViewStubEditor;
use Illuminate\Support\Facades\Config;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class ViewStubEditorTest extends TestCase
{
    /**
     * A test to get the page view stub file for Vue frontend scaffolding.
     *
     * @return void
     */
    public function test_get_page_stub_file_for_vue_frontend()
    {
        $frontendScaffolding = 'vue';
        $stubsLocation = 'stubs/cruddy';
        $type = 'page';
        $expectedResult = dirname(dirname(dirname(__DIR__))) . '/Commands/' . $stubsLocation . '/views/' . $frontendScaffolding . '/' . $type . '.stub';

        $mock = $this->partialMock(ViewStubEditor::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->never();
        });

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->andReturn($stubsLocation);

        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->andReturn($frontendScaffolding);

        $result = $mock->getStubFile();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the index view stub file for default frontend scaffolding.
     *
     * @return void
     */
    public function test_get_index_stub_file_for_default_frontend()
    {
        $frontendScaffolding = 'default';
        $stubsLocation = 'stubs/cruddy';
        $type = 'index';
        $expectedResult = dirname(dirname(dirname(__DIR__))) . '/Commands/' . $stubsLocation . '/views/' . $frontendScaffolding . '/' . $type . '.stub';

        $mock = $this->partialMock(ViewStubEditor::class, function (MockInterface $mock) use ($type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
        });

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->andReturn($stubsLocation);

        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->andReturn($frontendScaffolding);

        $result = $mock->getStubFile();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the create view stub file for default frontend scaffolding.
     *
     * @return void
     */
    public function test_get_create_stub_file_for_default_frontend()
    {
        $frontendScaffolding = 'default';
        $stubsLocation = 'stubs/cruddy';
        $type = 'create';
        $expectedResult = dirname(dirname(dirname(__DIR__))) . '/Commands/' . $stubsLocation . '/views/' . $frontendScaffolding . '/' . $type . '.stub';

        $mock = $this->partialMock(ViewStubEditor::class, function (MockInterface $mock) use ($type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
        });

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->andReturn($stubsLocation);

        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->andReturn($frontendScaffolding);

        $result = $mock->getStubFile();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the show view stub file for default frontend scaffolding.
     *
     * @return void
     */
    public function test_get_show_stub_file_for_default_frontend()
    {
        $frontendScaffolding = 'default';
        $stubsLocation = 'stubs/cruddy';
        $type = 'show';
        $expectedResult = dirname(dirname(dirname(__DIR__))) . '/Commands/' . $stubsLocation . '/views/' . $frontendScaffolding . '/' . $type . '.stub';

        $mock = $this->partialMock(ViewStubEditor::class, function (MockInterface $mock) use ($type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
        });

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->andReturn($stubsLocation);

        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->andReturn($frontendScaffolding);

        $result = $mock->getStubFile();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the edit view stub file for default frontend scaffolding.
     *
     * @return void
     */
    public function test_get_edit_stub_file_for_default_frontend()
    {
        $frontendScaffolding = 'default';
        $stubsLocation = 'stubs/cruddy';
        $type = 'edit';
        $expectedResult = dirname(dirname(dirname(__DIR__))) . '/Commands/' . $stubsLocation . '/views/' . $frontendScaffolding . '/' . $type . '.stub';

        $mock = $this->partialMock(ViewStubEditor::class, function (MockInterface $mock) use ($type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
        });

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->andReturn($stubsLocation);

        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->andReturn($frontendScaffolding);

        $result = $mock->getStubFile();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the page view stub file with default frontend scaffolding, which should not be an acceptabe combination.
     *
     * @return void
     */
    public function test_get_page_stub_file_for_default_frontend()
    {
        $frontendScaffolding = 'default';
        $stubsLocation = 'stubs/cruddy';
        $type = 'page';
        $expectedResultComparison = dirname(dirname(dirname(__DIR__))) . '/Commands/' . $stubsLocation . '/views/' . $frontendScaffolding . '/' . $type . '.stub';

        $mock = $this->partialMock(ViewStubEditor::class, function (MockInterface $mock) use ($type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
        });

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->andReturn($stubsLocation);

        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->andReturn($frontendScaffolding);

        $result = $mock->getStubFile();

        $this->assertNotSame($expectedResultComparison, $result);
    }

}