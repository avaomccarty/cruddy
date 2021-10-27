<?php

namespace Cruddy\Tests\Unit\Traits\Stubs;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\ConfigTrait;
use Cruddy\Traits\Stubs\FormTrait;
use Cruddy\Traits\Stubs\StubTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class FormTraitTest extends TestCase
{
    use CommandTrait, ConfigTrait, FormTrait, StubTrait, TestTrait;
}