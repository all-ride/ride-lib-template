# Ride: Template Library

Template abstraction library of the PHP Ride framework.

## Engine

The implementation of _Engine_ will implement the actual template engine through the _Template_ interface.
A template engine decides the features and the syntax (or language) to write in.

## EngineModel

An _EngineModel_ is an interface for the data store of the available template engines.

## Theme

Optional support for template themes through the _Theme_ interface.
Each theme can implement it's own version of a template.
This makes it possible to return a different representation of the same data.

A theme chooses the engines it's available in.

You can make a theme hierarchical by defining it's parent.
This makes it easy to create subthemes and helps the engine to fallback when a resource could not be found.

## ThemeModel

A _ThemeModel_ is an interface for the data store of the available themes.

## Template

A _Template_ instance defines the resource and the variables to render the template.
With a _ThemedTemplate_, you can specify a template in a specific theme.

The resource should be generic so it's not bound to a specific template engine.
For example: use _base/index_ instead of _view/smarty/themes/bootstrap/base/index.tpl_.

## Code Sample

Check this code sample to get an idea of this library:

```php
<?php

use ride\library\template\engine\EngineModel;
use ride\library\template\engine\ThemeModel;
use ride\library\template\TemplateFacade;

function foo(EngineModel $engineModel, ThemeModel $themeModel) {
    $resource = 'path/to/resource';
    $variables = array('var1' => 'value1');

    // template facade should be made available by your implementation
    $templateFacade = TemplateFacade($engineModel, $themeModel);
    $templateFacade->setDefaultEngine('smarty');
    $templateFacade->setDefaultTheme('my-theme');

    // a simple template rendering
    $template = $templateFacade->createTemplate($resource, $variables);
    $template->set('var2', 'value2');

    $output = $templateFacade->render($template);

    // a template for a non-default theme
    $template = $templateFacade->createTemplate($resource, $variables, 'overriden-theme');

    // a template for a non-default theme with a specific engine
    $template = $templateFacade->createTemplate($resource, $variables, 'overriden-theme', 'my-engine');

    // get the file representation of a specific template
    $file = $templateFacade->getFile($template); // ride\library\system\file\File

    // get's the available template for a specific namespace
    $engine = null;
    $theme = null;
    $templates = $templateFacade->getFiles('path/to', $engine, $theme);
    echo $engine; // smarty
    echo $theme; // my-theme

    // reads the meta from the template comments in the beginning of the resource
    // syntax: [key: value([; key: value])*]
    // eg for Smarty: {* name: My Title; action: index *}
    // will return array('name' => 'My Title', 'action' => 'index')
    $meta = $templateFacade->getTemplateMeta($template);
}
```

For more concrete implementations, check the ride-lib-template* and ride-app-template* modules.
