<?php

namespace pallo\library\template;

use pallo\library\template\engine\EngineModel;
use pallo\library\template\exception\ResourceNotSetException;
use pallo\library\template\exception\TemplateException;
use pallo\library\template\theme\Theme;
use pallo\library\template\theme\ThemeModel;

/**
 * Generic template renderer
 */
class TemplateFacade {

    /**
     * Instance of the template engine model
     * @var pallo\library\template\engine\EngineModel
     */
    protected $engineModel;

    /**
     * Instance of the theme model
     * @var pallo\library\template\theme\ThemeModel
     */
    protected $themeModel;

    /**
     * Machine name of the default template engine
     * @var string
     */
    protected $defaultEngine;

    /**
     * Machine name of the default theme
     * @var string
     */
    protected $defaultTheme;

    /**
     * Constructs a new template facade
     * @param pallo\library\template\engine\EngineModel $engineModel
     * @param pallo\library\template\theme\ThemeModel $themeModel
     * @return null
     */
    public function __construct(EngineModel $engineModel, ThemeModel $themeModel) {
        $this->engineModel = $engineModel;
        $this->themeModel = $themeModel;
    }

    /**
     * Gets the template engine model
     * @return pallo\library\template\engine\EngineModel
     */
    public function getEngineModel() {
        return $this->engineModel;
    }

    /**
     * Gets the theme model
     * @return pallo\library\template\theme\ThemeModel
     */
    public function getThemeModel() {
        return $this->themeModel;
    }

    /**
     * Sets the default template engine
     * @param string $engine Machine name of the template engine
     * @return null
     */
    public function setDefaultEngine($engine) {
        $this->defaultEngine = $engine;
    }

    /**
     * Sets the default theme
     * @param string $theme Machine name of the theme
     * @return null
     */
    public function setDefaultTheme($theme) {
        $this->defaultTheme = $theme;
    }

    /**
     * Creates a new template
     * @param string $resource Resource name of the template
     * @param array $variables Variables for the template
     * @param string $theme Machine name of the template theme
     * @param string $engine Machine name of the template engine
     * @return pallo\library\template\Template
     */
    public function createTemplate($resource, array $variables = null, $theme = null, $engine = null) {
        $theme = $this->getTheme($theme);
        $engine = $this->getEngine($engine, $theme);

        $template = $engine->createTemplate($resource, $variables);

        if ($theme && $template instanceof ThemedTemplate) {
            $template->setTheme($theme->getName());
        }

        return $template;
    }

    /**
     * Renders a template
     * @param pallo\library\template\Template $template Template to
     * render
     * @return string Rendered template
     * @throws pallo\library\template\exception\ResourceNotSetException when
     * no resource was set to the template
     * @throws pallo\library\template\exception\ResourceNotFoundException when
     * the template could not be found by the engine
     */
    public function render(Template $template) {
        $resource = $template->getResource();
        if (!$resource) {
            throw new ResourceNotSetException();
        }

        if ($template instanceof ThemedTemplate) {
            $theme = $this->getTheme($template->getTheme());
        } else {
            $theme = null;
        }

        return $this->getEngine($template->getEngine(), $theme)->render($template);
    }

    /**
     * Gets the template theme
     * @param string $theme Machine name of theme theme
     * @return pallo\library\template\theme\Theme|null
     */
    protected function getTheme($theme) {
        if ($theme) {
            return $this->themeModel->getTheme($theme);
        } elseif ($this->defaultTheme) {
            return $this->themeModel->getTheme($this->defaultTheme);
        }

        return null;
    }

    /**
     * Gets the template engine
     * @param string $engine Machine name of the template engine
     * @param pallo\library\template\theme\Theme $theme
     * @return pallo\library\template\engine\Engine
     */
    protected function getEngine($engine, Theme $theme = null) {
        if ($engine && $theme) {
            $engines = $theme->getEngines();

            if ($engines && $engines != $engine && (is_array($engines) && !in_array($engine, $engines))) {
                throw new TemplateException('Could not use template engine ' . $engine . ': not supported by theme ' . $theme->getName());
            }
        } elseif (!$engine && $theme) {
            $engines = $theme->getEngines();
            if ($engines) {
                if (is_array($engines)) {
                    $engine = array_shift($engines);
                } else {
                    $engine = $engines;
                }
            }
        }

        if (!$engine && $this->defaultEngine) {
            $engine = $this->defaultEngine;
        }

        if (!$engine) {
            throw new TemplateException('Could not determine template engine: no default engine set');
        }

        return $this->engineModel->getEngine($engine);
    }

}