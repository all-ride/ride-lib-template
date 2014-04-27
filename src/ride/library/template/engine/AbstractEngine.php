<?php

namespace ride\library\template\engine;

use ride\library\template\exception\TemplateException;
use ride\library\template\theme\Theme;
use ride\library\template\theme\ThemeModel;
use ride\library\template\GenericThemedTemplate;
use ride\library\template\Template;
use ride\library\template\ThemedTemplate;

/**
 * Abstract implementation for a template engine
 */
abstract class AbstractEngine implements Engine {

    /**
     * Instance of the theme model
     * @var ride\library\templat\theme\ThemeModel
     */
    protected $themeModel;

    /**
     * Gets the name of this template engine
     * @return string
     */
    public function getName() {
        return static::NAME;
    }

    /**
     * Gets the extension for the template resources
     * @return string
     */
    public function getExtension() {
        return static::EXTENSION;
    }

    /**
     * Sets the model of themes
     * @param ride\library\template\theme\ThemeModel $themeModel
     * @return null
     */
    public function setThemeModel(ThemeModel $themeModel) {
        $this->themeModel = $themeModel;
    }

    /**
     * Gets the themes which support this engine
     * @return array Array with the name of the theme as key and the true as
     * value
     */
    public function getThemes() {
        if (!$this->themeModel) {
            throw new TemplateException('Could not get the engine themes: no theme model set');
        }

        $themes = $this->themeModel->getThemes();
        foreach ($themes as $index => $theme) {
            $engines = $theme->getEngines();

            if ($engines && $engines != static::NAME && (is_array($engines) && !in_array(static::NAME, $engines))) {
                unset($themes[$index]);
            }
        }

        return $themes;
    }

    /**
     * Gets the theme hierarchy from a template
     * @param ride\library\template\Template $template
     * @return array Array with the theme name as key and in order of hierarchy
     */
    protected function getTheme(Template $template) {
        if (!$template instanceof ThemedTemplate) {
            return null;
        }

        $theme = $template->getTheme();
        if (!$theme) {
            return null;
        }

        if (!$this->themeModel) {
            throw new TemplateException('Could not get the theme hierarchy: no theme model set');
        }
        $theme = $this->themeModel->getTheme($theme);

        return $this->getThemeHierarchy($theme);
    }

    /**
     * Gets the theme hierarchy from a theme
     * @param ride\library\template\theme\Theme $theme
     * @return array Array with the theme name as key and in order of hierarchy
     */
    protected function getThemeHierarchy(Theme $theme = null) {
        if (!$theme) {
            return array();
        }

        $result = array($theme->getName() => true);

        $parent = $theme->getParent();
        if (!$parent) {
            return $result;
        }

        $parent = $this->themeModel->getTheme($parent);

        $result += $this->getThemeHierarchy($parent);

        return $result;
    }

    /**
     * Creates a template for this engine
     * @param string $resource Resource to the template
     * @param array $variables Variables for the template
     * @return Template
     */
    public function createTemplate($resource, array $variables = null) {
        $template = $this->createTemplateInstance();
        $template->setResource($resource);
        $template->set($variables);

        return $template;
    }

    /**
     * Creates a new instance of a template
     * @return ride\library\template\Template
     */
    protected function createTemplateInstance() {
        $template = new GenericThemedTemplate();
        $template->setEngine(static::NAME);

        return $template;
    }

}
