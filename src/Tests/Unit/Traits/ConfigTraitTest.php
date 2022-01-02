<?php

namespace Cruddy\Tests\Unit\Traits;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\ConfigTrait;
use Illuminate\Support\Facades\Config;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class ConfigTraitTest extends TestCase
{
    use ConfigTrait, TestTrait;

    /**
     * A test to get the frontend scaffolding from the config.
     *
     * @return void
     */
    public function test_getting_frontend_scaffolding()
    {
        $expectedResult = $frontendScaffolding = 'frontendScaffolding';

        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->once()
            ->andReturn($frontendScaffolding);
        
        $result = $this->getFrontendScaffoldingName();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the frontend scaffolding from the config default.
     *
     * @return void
     */
    public function test_getting_frontend_scaffolding_default()
    {
        $expectedResult = 'default';

        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->once()
            ->andReturn(null);
        
        $result = $this->getFrontendScaffoldingName();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the stubs location.
     *
     * @return void
     */
    public function test_getting_stubs_location()
    {
        $expectedResult = $location = 'location';

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->once()
            ->andReturn($location);
        
        $result = $this->getStubsLocation();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to determine if it needs a Vue frontend when Vue used for frontend.
     *
     * @return void
     */
    public function test_needs_vue_frontend_when_frontend_is_vue()
    {
        $expectedResult = 'vue';

        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->once()
            ->andReturn($expectedResult);
        
        $result = $this->needsVueFrontend();

        $this->assertTrue($result);
    }

    /**
     * A test to determine if it needs a Vue frontend when Vue not used for frontend.
     *
     * @return void
     */
    public function test_needs_vue_frontend_when_frontend_is_not_vue()
    {
        $expectedResult = 'not-vue';

        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->once()
            ->andReturn($expectedResult);
        
        $result = $this->needsVueFrontend();

        $this->assertFalse($result);
    }

    /**
     * A test to get the Cruddy database connection.
     *
     * @return void
     */
    public function test_get_cruddy_database_connection()
    {
        $expectedResult = ['connection'];

        Config::shouldReceive('get')
            ->with('cruddy.database.connections.cruddy')
            ->once()
            ->andReturn($expectedResult);
        
        $result = $this->getCruddyDatabaseConnection();

        $this->assertIsArray($result);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to set the Cruddy database connection.
     *
     * @return void
     */
    public function test_set_cruddy_database_connection()
    {
        $connection = ['connection'];

        Config::shouldReceive('get')
            ->with('cruddy.database.connections.cruddy')
            ->once()
            ->andReturn($connection);

        Config::shouldReceive('set')
            ->with('database.connections.cruddy', $connection)
            ->once();
        
        $this->setDatabaseConnection();
    }

    /**
     * A test to get the Vue folder.
     *
     * @return void
     */
    public function test_get_vue_folder()
    {
        $expectedResult = 'expectedResult';

        Config::shouldReceive('get')
            ->with('cruddy.vue_folder')
            ->once()
            ->andReturn($expectedResult);
        
        $result = $this->getVueFolder();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test for if the resource should be an API.
     *
     * @return void
     */
    public function test_is_api()
    {
        $expectedResult = true;

        Config::shouldReceive('get')
            ->with('cruddy.is_api')
            ->once()
            ->andReturn($expectedResult);
        
        $result = $this->isApi();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test for if the resource needs UI.
     *
     * @return void
     */
    public function test_needs_ui()
    {
        $expectedResult = true;

        Config::shouldReceive('get')
            ->with('cruddy.needs_ui')
            ->once()
            ->andReturn($expectedResult);
        
        $result = $this->needsUI();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test for getting the Vue import file location.
     *
     * @return void
     */
    public function test_get_vue_import_file_location()
    {
        $expectedResult = 'expectedResult';

        Config::shouldReceive('get')
            ->with('cruddy.vue_import_file')
            ->once()
            ->andReturn($expectedResult);
        
        $result = $this->getVueImportFileLocation();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test for getting the validation default.
     *
     * @return void
     */
    public function test_get_validation_default()
    {
        $expectedResult = 'expectedResult';
        $type = 'string';

        Config::shouldReceive('get')
            ->with('cruddy.validation_defaults')
            ->once()
            ->andReturn([
                $type => $expectedResult
            ]);

        $result = $this->getValidationDefault($type);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test for getting the default for the input type.
     *
     * @return void
     */
    public function test_get_default_for_input_type()
    {
        $expectedResult = 'expectedResult';
        $type = 'string';

        Config::shouldReceive('get')
            ->with('cruddy.input_defaults')
            ->once()
            ->andReturn([
                $type => $expectedResult
            ]);

        $result = $this->getDefaultForInputType($type);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test for getting the input defaults.
     *
     * @return void
     */
    public function test_get_input_defaults()
    {
        $expectedResult = ['expectedResult'];

        Config::shouldReceive('get')
            ->with('cruddy.input_defaults')
            ->once()
            ->andReturn($expectedResult);
        
        $result = $this->getInputDefaults();

        $this->assertIsArray($result);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test for getting the vue component search string.
     *
     * @return void
     */
    public function test_get_vue_component_search_string()
    {
        $expectedResult = 'expectedResult';

        Config::shouldReceive('get')
            ->with('cruddy.vue_search_string')
            ->once()
            ->andReturn($expectedResult);
        
        $result = $this->getVueComponentSearchString();

        $this->assertIsString($result);
        $this->assertSame($expectedResult, $result);
    }
}