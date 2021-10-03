<?php

namespace Cruddy\Tests\Feature;

use Cruddy\Commands\RequestMakeCommand;
use Cruddy\ServiceProvider;
use Cruddy\Traits\RequestMakeCommandTrait;
use Cruddy\Traits\CommandTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Schema\ColumnDefinition;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;
use stdClass;

class RequestMakeCommandTest extends TestCase
{
    use RequestMakeCommandTrait;

    /**
     * The output from successfully running the command.
     *
     * @var string
     */
    protected $successOutput = 'Cruddy request created successfully.';

    /**
     * The location of the stubs folder.
     *
     * @var string
     */
    protected $stubPath = '/stubs';

    protected $loadEnvironmentVariables = true;

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    public function setUp() : void
    {
        parent::setUp();
        $this->name = 'test';
    }

    /**
     * Get a usable stub.
     *
     * @param  array  $rules
     * @return string
     */
    protected function getExpectedRequestFile(array $rules = []) : string
    {
        $rulesString = '';
        foreach ($rules as $rule) {
            $rulesString .= "'";
            $rulesString .= $rule['name'];
            $rulesString .= "' => '";
            $rulesString .= Config::get('cruddy.validation_defaults.' . $rule['type']);
            if ($rule !== last($rules)) {
                $rulesString .= '|';
            }
        }

        return "
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class " . $this->getNameInput() . " extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            $rulesString
        ];
    }
}
";
    }

    /**
     * Get the stub file.
     *
     * @return string
     */
    public function getStub()
    {
        return "
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class " . $this->getNameInput() . " extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            {{ rules }}
        ];
    }
}
";
    }

    /**
     * Get the correct output file.
     *
     * @return string
     */
    protected function getExpectedOutputFile() : string
    {
        return "
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class " . $this->getNameInput() . " extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name1' => 'string',
\t\t\t'name2' => 'number',
        ];
    }
}
";
    }

    /**
     * A test for correct default stub file.
     *
     * @test
     * @group cruddy
     * @return void
     */
    public function test_correct_default_stub_file_used()
    {
        $fileLocation = app_path() . '/Http/Requests/' . $this->getNameInput() . '.php';
        $stubFile = 'request.stub';
        $stubPath = 'stubs/cruddy';
        $stubLocation = dirname(dirname(dirname(__DIR__))) . '/vendor/orchestra/testbench-core/laravel/' . $stubPath . '/' . $stubFile;

        File::shouldReceive('exists')
            ->with($fileLocation)
            ->once()
            ->andReturn(false);

        File::shouldReceive('exists')
            ->andReturn(true);

        File::shouldReceive('get')
            ->with($stubLocation)
            ->once()
            ->andReturn($this->getExpectedRequestFile());

        File::shouldReceive('put')
            ->with($fileLocation, $this->getExpectedRequestFile())
            ->once();

        File::partialMock();

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->once()
            ->andReturn($stubPath);

        Config::partialMock();

        $this->artisan('cruddy:request', [
            'name' => $this->name
        ])
            ->expectsOutput($this->successOutput)
            ->assertExitCode(0);
    }

    /**
     * A test for correct update stub file.
     *
     * @test
     * @group cruddy
     * @return void
     */
    public function test_correct_update_stub_file_used()
    {
        $fileLocation = app_path() . '/Http/Requests/UpdateTest.php';
        $stubFile = 'request.stub';
        $stubPath = 'stubs/cruddy';
        $stubLocation = dirname(dirname(dirname(__DIR__))) . '/vendor/orchestra/testbench-core/laravel/' . $stubPath . '/' . $stubFile;

        File::shouldReceive('exists')
            ->with($fileLocation)
            ->once()
            ->andReturn(false);

        File::shouldReceive('exists')
            ->andReturn(true);

        File::shouldReceive('get')
            ->with($stubLocation)
            ->once()
            ->andReturn($this->getExpectedRequestFile());

        File::shouldReceive('put')
            ->with($fileLocation, $this->getExpectedRequestFile())
            ->once();

        File::partialMock();

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->once()
            ->andReturn($stubPath);

        Config::partialMock();

        $this->artisan('cruddy:request', [
            'name' => $this->name,
            'type' => 'update'
        ])
            ->expectsOutput($this->successOutput)
            ->assertExitCode(0);
    }

    /**
     * A test for correct store stub file.
     *
     * @test
     * @group cruddy
     * @return void
     */
    public function test_correct_store_stub_file_used()
    {
        $fileLocation = app_path() . '/Http/Requests/StoreTest.php';
        $stubFile = 'request.stub';
        $stubPath = 'stubs/cruddy';
        $stubLocation = dirname(dirname(dirname(__DIR__))) . '/vendor/orchestra/testbench-core/laravel/' . $stubPath . '/' . $stubFile;

        File::shouldReceive('exists')
            ->with($fileLocation)
            ->once()
            ->andReturn(false);

        File::shouldReceive('exists')
            ->andReturn(true);

        File::shouldReceive('get')
            ->with($stubLocation)
            ->once()
            ->andReturn($this->getExpectedRequestFile());

        File::shouldReceive('put')
            ->with($fileLocation, $this->getExpectedRequestFile())
            ->once();

        File::partialMock();

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->once()
            ->andReturn($stubPath);

        Config::partialMock();

        $this->artisan('cruddy:request', [
            'name' => $this->name,
            'type' => 'store'
        ])
            ->expectsOutput($this->successOutput)
            ->assertExitCode(0);
    }

    /**
     * A test for using correct variables within the stub.
     *
     * @test
     * @group cruddy
     * @return void
     */
    public function test_correct_variables_used_in_stub()
    {
        $fileLocation = app_path() . '/Http/Requests/UpdateTest.php';
        $stubFile = 'request.stub';
        $stubPath = 'stubs/cruddy';
        $stubLocation = dirname(dirname(dirname(__DIR__))) . '/vendor/orchestra/testbench-core/laravel/' . $stubPath . '/' . $stubFile;

        $rule1 = new ColumnDefinition([
            'type' => 'string',
            'name' => 'name1',
            'length' => 100
        ]);
        
        $rule2 = new ColumnDefinition([
            'type' => 'number',
            'name' => 'name2',
            'min' => 1,
            'max' => 100,
        ]);

        $rules = [
            $rule1,
            $rule2
        ];

        $stub = $this->getStub();
        $outputFile = $this->getExpectedOutputFile();

        $mock = $this->partialMock(RequestMakeCommandTest::class, function (MockInterface $mock) use ($rules) {
            $mock->shouldReceive('getRules')
                ->andReturn($rules);
        });

        File::shouldReceive('exists')
            ->with($fileLocation)
            ->once()
            ->andReturn(false);

        File::shouldReceive('exists')
            ->andReturn(true);

        File::shouldReceive('get')
            ->with($stubLocation)
            ->once()
            ->andReturn($stub);
        
        $this->updateStubWithRules($outputFile, $rules);

        File::shouldReceive('put')
            ->with($fileLocation, $outputFile)
            ->once();

        File::partialMock();

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->once()
            ->andReturn($stubPath);

        foreach ($rules as $rule) {
            Config::shouldReceive('get')
                ->with('cruddy.validation_defaults.' . $rule->type)
                ->once()
                ->andReturn($rule->type);
        }

        Config::partialMock();

        $this->artisan('cruddy:request', [
            'name' => $this->name,
            'type' => 'update',
            'rules' => $rules
        ])
            ->expectsOutput($this->successOutput)
            ->assertExitCode(0);
    }
}