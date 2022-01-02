<?php

namespace Cruddy\StubEditors\Inputs\Input;

use Cruddy\Exceptions\StubEditors\Inputs\Input\UnknownStubInputEditorType;
use Illuminate\Database\Schema\ColumnDefinition;

class StubInputEditorFactory
{
    /**
     * Get the correct StubInputEditor
     *
     * @param  \Illuminate\Database\Schema\ColumnDefinition|null  $column = null
     * @param  string  $inputStubEditor = 'controller'
     * @param  string  &$stub = ''
     * @return StubInputEditor
     *
     * @throws \Cruddy\Exceptions\UnknownStubEditorType
     */
    public static function get(?ColumnDefinition $column = null, string $inputStubEditor = 'controller', string &$stub = '') : StubInputEditor
    {
        switch ($inputStubEditor) {
            case 'controller':
                return new ControllerStubInputEditor($column, $stub);
            case 'request':
                return new RequestStubInputEditor($column, $stub);
            case 'view':
                return new ViewStubInputEditor($column, $stub);
            default:
                throw new UnknownStubInputEditorType();
        }
    }
}