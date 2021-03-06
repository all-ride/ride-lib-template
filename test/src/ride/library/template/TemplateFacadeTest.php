<?php

namespace ride\library\template;

use \PHPUnit_Framework_TestCase;

class TemplateFacadeTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $engineModel = $this->getMockBuilder('ride\\library\\template\\engine\\EngineModel')
                            ->setMethods(array('getEngines', 'getEngine'))
                            ->getMock();
        $engineModel->expects($this->once())
                    ->method('getEngines')
                    ->will($this->returnValue(array()));

        $themeModel = $this->getMockBuilder('ride\\library\\template\\theme\\ThemeModel')
                           ->getMock();

        $templateFacade = new TemplateFacade($engineModel, $themeModel);

        $this->assertEquals($engineModel, $templateFacade->getEngineModel());
        $this->assertEquals($themeModel, $templateFacade->getThemeModel());
        $this->assertNull($templateFacade->getDefaultEngine());
        $this->assertNull($templateFacade->getDefaultTheme());
    }

    public function testSetThemeModelSetsThemeModelToEngines() {
        $themeModel = $this->getMockBuilder('ride\\library\\template\\theme\\ThemeModel')
                           ->getMock();

        $engine = $this->getMockBuilder('ride\\library\\template\\engine\\Engine')
                       ->setMethods(array('getName', 'getExtension', 'getBlockComment', 'setThemeModel', 'getThemes', 'createTemplate', 'render', 'getFile', 'getFiles'))
                       ->getMock();
        $engine->expects($this->once())
               ->method('setThemeModel')
               ->with($this->equalTo($themeModel))
               ->will($this->returnValue(null));

        $engines = array($engine);

        $engineModel = $this->getMockBuilder('ride\\library\\template\\engine\\EngineModel')
                            ->setMethods(array('getEngines', 'getEngine'))
                            ->getMock();
        $engineModel->expects($this->once())
                    ->method('getEngines')
                    ->will($this->returnValue($engines));

        new TemplateFacade($engineModel, $themeModel);
    }

    public function testCreateTemplate() {
        $resource = 'resource';
        $variables = array('var' => 'value');
        $engineName = 'engine';

        $themeModel = $this->getMockBuilder('ride\\library\\template\\theme\\ThemeModel')
                           ->setMethods(array('getThemes', 'getTheme'))
                           ->getMock();

        $engine = $this->getMockBuilder('ride\\library\\template\\engine\\Engine')
                       ->setMethods(array('getName', 'getExtension', 'getBlockComment', 'setThemeModel', 'getThemes', 'createTemplate', 'render', 'getFile', 'getFiles'))
                       ->getMock();
        $engine->expects($this->once())
               ->method('setThemeModel')
               ->with($this->equalTo($themeModel))
               ->will($this->returnValue(null));
        $engine->expects($this->once())
               ->method('getName')
               ->will($this->returnValue($engineName));

        $engines = array($engine);

        $engineModel = $this->getMockBuilder('ride\\library\\template\\engine\\EngineModel')
                            ->setMethods(array('getEngines', 'getEngine'))
                            ->getMock();
        $engineModel->expects($this->any())
                    ->method('getEngines')
                    ->will($this->returnValue($engines));
        $engineModel->expects($this->once())
                    ->method('getEngine')
                    ->with($this->equalTo($engineName))
                    ->will($this->returnValue($engine));

        $template = new GenericTemplate();
        $template->setResource($resource);
        $template->setEngine($engineName);
        $template->set($variables);

        $engine->expects($this->once())
               ->method('createTemplate')
               ->with($this->equalTo($resource), $this->equalTo($variables))
               ->will($this->returnValue($template));

        $templateFacade = new TemplateFacade($engineModel, $themeModel);

        $createdTemplate = $templateFacade->createTemplate($resource, $variables);

        $this->assertEquals($template, $createdTemplate);
    }

    public function testCreateTemplateWithTheme() {
        $resource = 'resource';
        $variables = array('var' => 'value');

        $engines = array();
        $engineName = 'engine';

        $themes = array();
        $themeName = 'theme';

        $theme = $this->getMockBuilder('ride\\library\\template\\theme\\Theme')
                      ->setMethods(array('getName', 'getDisplayName', 'getParent', 'getEngines'))
                      ->getMock();
        $theme->expects($this->once())
              ->method('getName')
              ->will($this->returnValue($themeName));
        $theme->expects($this->once())
              ->method('getEngines')
              ->will($this->returnValue(array($engineName)));

        $themes[] = $theme;

        $themeModel = $this->getMockBuilder('ride\\library\\template\\theme\\ThemeModel')
                           ->setMethods(array('getThemes', 'getTheme'))
                           ->getMock();
        $themeModel->expects($this->once())
                   ->method('getTheme')
                   ->with($this->equalTo($themeName))
                   ->will($this->returnValue($theme));

        $engine = $this->getMockBuilder('ride\\library\\template\\engine\\Engine')
                       ->setMethods(array('getName', 'getExtension', 'getBlockComment', 'setThemeModel', 'getThemes', 'createTemplate', 'render', 'getFile', 'getFiles'))
                       ->getMock();

        $engines[] = $engine;

        $engine = $this->getMockBuilder('ride\\library\\template\\engine\\Engine')
                       ->setMethods(array('getName', 'getExtension', 'getBlockComment', 'setThemeModel', 'getThemes', 'createTemplate', 'render', 'getFile', 'getFiles'))
                       ->getMock();
        $engine->expects($this->once())
               ->method('setThemeModel')
               ->with($this->equalTo($themeModel))
               ->will($this->returnValue(null));

        $engines[] = $engine;

        $engineModel = $this->getMockBuilder('ride\\library\\template\\engine\\EngineModel')
                            ->setMethods(array('getEngines', 'getEngine'))
                            ->getMock();
        $engineModel->expects($this->any())
                    ->method('getEngines')
                    ->will($this->returnValue($engines));
        $engineModel->expects($this->once())
                    ->method('getEngine')
                    ->with($this->equalTo($engineName))
                    ->will($this->returnValue($engine));

        $template = new GenericThemedTemplate();
        $template->setResource($resource);
        $template->setEngine($engineName);
        $template->set($variables);

        $engine->expects($this->once())
               ->method('createTemplate')
               ->with($this->equalTo($resource), $this->equalTo($variables))
               ->will($this->returnValue($template));

        $templateFacade = new TemplateFacade($engineModel, $themeModel);

        $createdTemplate = $templateFacade->createTemplate($resource, $variables, $themeName);

        $this->assertEquals($template, $createdTemplate);
        $this->assertEquals($themeName, $createdTemplate->getTheme());
    }

    public function testCreateTemplateWithEngine() {
        $resource = 'resource';
        $variables = array('var' => 'value');

        $engines = array();
        $engineName = 'engine';

        $themes = array();
        $themeName = 'theme';

        $theme = $this->getMockBuilder('ride\\library\\template\\theme\\Theme')
                      ->setMethods(array('getName', 'getDisplayName', 'getParent', 'getEngines'))
                      ->getMock();

        $themes[] = $theme;

        $themeModel = $this->getMockBuilder('ride\\library\\template\\theme\\ThemeModel')
                           ->setMethods(array('getThemes', 'getTheme'))
                           ->getMock();

        $engine = $this->getMockBuilder('ride\\library\\template\\engine\\Engine')
                       ->setMethods(array('getName', 'getExtension', 'getBlockComment', 'setThemeModel', 'getThemes', 'createTemplate', 'render', 'getFile', 'getFiles'))
                       ->getMock();

        $engines[] = $engine;

        $engine = $this->getMockBuilder('ride\\library\\template\\engine\\Engine')
                       ->setMethods(array('getName', 'getExtension', 'getBlockComment', 'setThemeModel', 'getThemes', 'createTemplate', 'render', 'getFile', 'getFiles'))
                       ->getMock();
        $engine->expects($this->once())
               ->method('setThemeModel')
               ->with($this->equalTo($themeModel))
               ->will($this->returnValue(null));

        $engines[] = $engine;

        $engineModel = $this->getMockBuilder('ride\\library\\template\\engine\\EngineModel')
                            ->setMethods(array('getEngines', 'getEngine'))
                            ->getMock();
        $engineModel->expects($this->once())
                    ->method('getEngines')
                    ->will($this->returnValue($engines));
        $engineModel->expects($this->once())
                    ->method('getEngine')
                    ->with($this->equalTo($engineName))
                    ->will($this->returnValue($engine));

        $template = new GenericTemplate();
        $template->setResource($resource);
        $template->setEngine($engineName);
        $template->set($variables);

        $engine->expects($this->once())
               ->method('createTemplate')
               ->with($this->equalTo($resource), $this->equalTo($variables))
               ->will($this->returnValue($template));

        $templateFacade = new TemplateFacade($engineModel, $themeModel);

        $createdTemplate = $templateFacade->createTemplate($resource, $variables, null, $engineName);

        $this->assertEquals($template, $createdTemplate);
    }

    public function testCreateTemplateWithEngineAndTheme() {
        $resource = 'resource';
        $variables = array('var' => 'value');

        $engines = array();
        $engineName = 'engine';

        $themes = array();
        $themeName = 'theme';

        $theme = $this->getMockBuilder('ride\\library\\template\\theme\\Theme')
                      ->setMethods(array('getName', 'getDisplayName', 'getParent', 'getEngines'))
                      ->getMock();

        $themes[] = $theme;

        $theme = $this->getMockBuilder('ride\\library\\template\\theme\\Theme')
                      ->setMethods(array('getName', 'getDisplayName', 'getParent', 'getEngines'))
                      ->getMock();
        $theme->expects($this->once())
              ->method('getName')
              ->will($this->returnValue($themeName));
        $theme->expects($this->once())
              ->method('getEngines')
              ->will($this->returnValue(array($engineName)));

        $themes[] = $theme;

        $themeModel = $this->getMockBuilder('ride\\library\\template\\theme\\ThemeModel')
                           ->setMethods(array('getThemes', 'getTheme'))
                           ->getMock();
        $themeModel->expects($this->once())
                   ->method('getTheme')
                   ->with($this->equalTo($themeName))
                   ->will($this->returnValue($theme));

        $engine = $this->getMockBuilder('ride\\library\\template\\engine\\Engine')
                       ->setMethods(array('getName', 'getExtension', 'getBlockComment', 'setThemeModel', 'getThemes', 'createTemplate', 'render', 'getFile', 'getFiles'))
                       ->getMock();

        $engines[] = $engine;

        $engine = $this->getMockBuilder('ride\\library\\template\\engine\\Engine')
                       ->setMethods(array('getName', 'getExtension', 'getBlockComment', 'setThemeModel', 'getThemes', 'createTemplate', 'render', 'getFile', 'getFiles'))
                       ->getMock();
        $engine->expects($this->once())
               ->method('setThemeModel')
               ->with($this->equalTo($themeModel))
               ->will($this->returnValue(null));

        $engines[] = $engine;

        $engineModel = $this->getMockBuilder('ride\\library\\template\\engine\\EngineModel')
                            ->setMethods(array('getEngines', 'getEngine'))
                            ->getMock();
        $engineModel->expects($this->once())
                    ->method('getEngines')
                    ->will($this->returnValue($engines));
        $engineModel->expects($this->once())
                    ->method('getEngine')
                    ->with($this->equalTo($engineName))
                    ->will($this->returnValue($engine));

        $template = new GenericThemedTemplate();
        $template->setResource($resource);
        $template->setEngine($engineName);
        $template->set($variables);

        $engine->expects($this->once())
               ->method('createTemplate')
               ->with($this->equalTo($resource), $this->equalTo($variables))
               ->will($this->returnValue($template));

        $templateFacade = new TemplateFacade($engineModel, $themeModel);

        $createdTemplate = $templateFacade->createTemplate($resource, $variables, $themeName, $engineName);

        $this->assertEquals($template, $createdTemplate);
        $this->assertEquals($themeName, $createdTemplate->getTheme());
    }

    /**
     * @expectedException ride\library\template\exception\TemplateException
     */
    public function testCreateTemplateWithEngineAndThemeThrowExceptionWhenEngineNotAvailableForTheme() {
        $resource = 'resource';
        $variables = array('var' => 'value');

        $engines = array();
        $engineName = 'engine';

        $themes = array();
        $themeName = 'theme';

        $theme = $this->getMockBuilder('ride\\library\\template\\theme\\Theme')
                      ->setMethods(array('getName', 'getDisplayName', 'getParent', 'getEngines'))
                      ->getMock();
        $theme->expects($this->once())
              ->method('getEngines')
              ->will($this->returnValue(array($engineName)));

        $themes[] = $theme;

        $themeModel = $this->getMockBuilder('ride\\library\\template\\theme\\ThemeModel')
                           ->setMethods(array('getThemes', 'getTheme'))
                           ->getMock();
        $themeModel->expects($this->once())
                   ->method('getTheme')
                   ->with($this->equalTo($themeName))
                   ->will($this->returnValue($theme));

        $engine = $this->getMockBuilder('ride\\library\\template\\engine\\Engine')
                       ->setMethods(array('getName', 'getExtension', 'getBlockComment', 'setThemeModel', 'getThemes', 'createTemplate', 'render', 'getFile', 'getFiles'))
                       ->getMock();

        $engines[] = $engine;

        $engine = $this->getMockBuilder('ride\\library\\template\\engine\\Engine')
                       ->setMethods(array('getName', 'getExtension', 'getBlockComment', 'setThemeModel', 'getThemes', 'createTemplate', 'render', 'getFile', 'getFiles'))
                       ->getMock();
        $engine->expects($this->once())
               ->method('setThemeModel')
               ->with($this->equalTo($themeModel))
               ->will($this->returnValue(null));

        $engines[] = $engine;

        $engineModel = $this->getMockBuilder('ride\\library\\template\\engine\\EngineModel')
                            ->setMethods(array('getEngines', 'getEngine'))
                            ->getMock();
        $engineModel->expects($this->once())
                    ->method('getEngines')
                    ->will($this->returnValue($engines));

        $templateFacade = new TemplateFacade($engineModel, $themeModel);
        $templateFacade->createTemplate($resource, $variables, $themeName, 'unknown');
    }

    /**
     * @expectedException ride\library\template\exception\TemplateException
     */
    public function testCreateTemplateThrowsExceptionWhenNoEnginesAvailable() {
        $resource = 'resource';
        $variables = array('var' => 'value');

        $themeModel = $this->getMockBuilder('ride\\library\\template\\theme\\ThemeModel')
                           ->setMethods(array('getThemes', 'getTheme'))
                           ->getMock();

        $engineModel = $this->getMockBuilder('ride\\library\\template\\engine\\EngineModel')
                            ->setMethods(array('getEngines', 'getEngine'))
                            ->getMock();
        $engineModel->expects($this->any())
                    ->method('getEngines')
                    ->will($this->returnValue(array()));

        $templateFacade = new TemplateFacade($engineModel, $themeModel);
        $templateFacade->createTemplate($resource, $variables);
    }

    /**
     * @dataProvider providerGetTemplateMeta
     */
    public function testGetTemplateMeta($expected, $content) {
        $resource = 'resource';
        $variables = array('var' => 'value');

        $engines = array();
        $engineName = 'engine';

        $themes = array();
        $themeName = 'theme';

        $blockComment = array('{*', '*}');

        $template = new GenericTemplate();
        $template->setResource($resource);
        $template->setEngine($engineName);
        $template->set($variables);

        $fs = $this->getMockBuilder('ride\\library\\system\\file\\FileSystem')
                   ->getMock();

        $file = $this->getMockBuilder('ride\\library\\system\\file\\File')
                     ->setConstructorArgs(array($fs, '/path/to/file'))
                     ->getMock();
        $file->expects($this->once())
             ->method('read')
             ->will($this->returnValue($content));

        $themeModel = $this->getMockBuilder('ride\\library\\template\\theme\\ThemeModel')
                           ->setMethods(array('getThemes', 'getTheme'))
                           ->getMock();

        $engine = $this->getMockBuilder('ride\\library\\template\\engine\\Engine')
                       ->setMethods(array('getName', 'getExtension', 'getBlockComment', 'setThemeModel', 'getThemes', 'createTemplate', 'render', 'getFile', 'getFiles'))
                       ->getMock();
        $engine->expects($this->any())
               ->method('getBlockComment')
               ->will($this->returnValue($blockComment));
        $engine->expects($this->once())
               ->method('getFile')
               ->with($this->equalTo($template))
               ->will($this->returnValue($file));

        $engines[] = $engine;

        $engineModel = $this->getMockBuilder('ride\\library\\template\\engine\\EngineModel')
                            ->setMethods(array('getEngines', 'getEngine'))
                            ->getMock();
        $engineModel->expects($this->any())
                    ->method('getEngines')
                    ->will($this->returnValue($engines));
        $engineModel->expects($this->any())
                    ->method('getEngine')
                    ->with($this->equalTo($engineName))
                    ->will($this->returnValue($engine));

        $engine->expects($this->once())
               ->method('createTemplate')
               ->with($this->equalTo($resource), $this->equalTo($variables))
               ->will($this->returnValue($template));

        $templateFacade = new TemplateFacade($engineModel, $themeModel);
        $template = $templateFacade->createTemplate($resource, $variables, null, $engineName);
        $meta = $templateFacade->getTemplateMeta($template);

        $this->assertEquals($expected, $meta);
    }

    public function providerGetTemplateMeta() {
        return array(
            array(
array(
    'name' => 'My template',
    'action' => 'index',
),
'{* name: My template; action: index *}
<h1>{$title}</h1>
<p>{$description}</p>',
            ),
            array(
array(
),
'<h1>{$title}</h1>
<p>{$description}</p>',
            ),
            array(
array(
),
'',
            ),
        );
    }

}
