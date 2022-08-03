<?php

namespace Cruddy\Tests;

use Illuminate\Support\Facades\App;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Cruddy\StubEditors\Inputs\InputsStubInteractor;
use Cruddy\Stubs\WithPlaceholders\Files\WithInputs\ControllerStub;
use Cruddy\Stubs\WithPlaceholders\Files\WithInputs\ModelStub;
use Cruddy\Stubs\WithPlaceholders\Files\WithInputs\RequestStub;

abstract class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        // Bind the InputsStubInteractor
       App::bind(InputsStubInteractor::class, function ($app, $params) {
            switch ($params[1]) {
                case 'model':
                    return (new ModelStub($params[0], $params[1]))
                        ->getStub();
                    break;
                case 'request':
                    return (new RequestStub($params[0], $params[1]))
                        ->getStub();
                    break;
                default:
                    return (new ControllerStub($params[0], $params[1]))
                        ->getStub();
                    break;
            }

            return (new InputsStubInteractor(...$params))
                ->getStub();
        });
    }
}