<?php

namespace ride\library\template;

use \PHPUnit_Framework_TestCase;

class GenericTemplateTest extends PHPUnit_Framework_TestCase {

    public function testResource() {
        $resource = 'resource';

        $template = new GenericTemplate();

        $this->assertNull($template->getResource());

        $template->setResource($resource);

        $this->assertEquals($resource, $template->getResource());

        $template->setResource(null);

        $this->assertNull($template->getResource());
    }

    public function testEngine() {
        $engine = 'engine';

        $template = new GenericTemplate();

        $this->assertNull($template->getEngine());

        $template->setEngine($engine);

        $this->assertEquals($engine, $template->getEngine());

        $template->setEngine(null);

        $this->assertNull($template->getEngine());
    }

    public function testVariables() {
        $variables = array(
            'var1' => 'value1',
            'var2' => 'value2',
        );
        $variable = 'variable';
        $value = 'value';

        $template = new GenericTemplate();

        $this->assertNull($template->get($variable));
        $this->assertEquals(array(), $template->getVariables());
        $this->assertEquals('default', $template->get($variable, 'default'));

        $template->set($variable, $value);

        $this->assertEquals($value, $template->get($variable));
        $this->assertEquals(array($variable => $value), $template->getVariables());

        $template->set($variables);

        $this->assertEquals('value1', $template->get('var1'));
        $this->assertEquals($variables + array($variable => $value), $template->getVariables());
    }

}
