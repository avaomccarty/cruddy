<?php

namespace Cruddy\Tests\Unit\Traits\Stubs;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\Stubs\FormTrait;
use Cruddy\Traits\Stubs\ModelTrait;
use Cruddy\Traits\Stubs\VariableTrait;
use Cruddy\Traits\Stubs\VueTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class VueTraitTest extends TestCase
{
    use CommandTrait, ModelTrait, FormTrait, VueTrait, VariableTrait, TestTrait;
}