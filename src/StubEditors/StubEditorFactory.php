<?php

namespace Cruddy\StubEditors;

use Cruddy\Exceptions\UnknownStubEditorType;
use Cruddy\StubEditors\StubEditor;

class StubEditorFactory
{
    /**
     * Get the correct StubEditor
     *
     * @param  string  $stubEditor = 'controller'
     * @param  string  &$stub = ''
     * @return \Cruddy\StubEditors\StubEditor
     *
     * @throws \Cruddy\Exceptions\UnknownStubEditorType
     */
    public static function get(string $stubEditor = 'controller', string &$stub = '') : StubEditor
    {
        switch ($stubEditor) {
            case 'controller':
                return new ControllerStubEditor($stub);
            case 'model':
                return new ModelStubEditor($stub);
            case 'request':
                return new RequestStubEditor($stub);
            case 'view':
                return new ViewStubEditor($stub);
            default:
                throw new UnknownStubEditorType();
        }
    }
}