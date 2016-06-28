<?php

namespace ride\library\template;

use \PHPUnit_Framework_TestCase;

class GenericThemedTemplateTest extends PHPUnit_Framework_TestCase {

    public function testResourceId() {
        $resourceId = 'resourceId';

        $template = new GenericThemedTemplate();

        $this->assertNull($template->getResourceId());

        $template->setResourceId($resourceId);

        $this->assertEquals($resourceId, $template->getResourceId());

        $template->setResourceId(null);

        $this->assertNull($template->getResourceId());
    }

    public function testTheme() {
        $theme = 'theme';

        $template = new GenericThemedTemplate();

        $this->assertNull($template->getTheme());

        $template->setTheme($theme);

        $this->assertEquals($theme, $template->getTheme());

        $template->setTheme(null);

        $this->assertNull($template->getTheme());
    }

}
