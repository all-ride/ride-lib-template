# Pallo: Template Library

Template abstraction library of the PHP Pallo framework.

## Template

A _Template_ instance defines the resource and the variables to render the template.

The resource should be generic so it's not bound to a specific template engine.
For example: use _base/index_ instead of _view/smarty/themes/bootstrap/base/index.tpl_.

## TemplateEngine

The implementation of _TemplateEngine_ will implement the actual template engine with the _Template_ interface.