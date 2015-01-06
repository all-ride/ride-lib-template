<?php

namespace ride\library\template;

use ride\library\template\engine\EngineModel;
use ride\library\template\exception\ResourceNotSetException;
use ride\library\template\exception\TemplateException;
use ride\library\template\theme\Theme;
use ride\library\template\theme\ThemeModel;

/**
 * Generic template renderer
 */
class TemplateFacade {

    /**
     * Instance of the template engine model
     * @var \ride\library\template\engine\EngineModel
     */
    protected $engineModel;

    /**
     * Instance of the theme model
     * @var \ride\library\template\theme\ThemeModel
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
     * @param \ride\library\template\engine\EngineModel $engineModel
     * @param \ride\library\template\theme\ThemeModel $themeModel
     * @return null
     */
    public function __construct(EngineModel $engineModel, ThemeModel $themeModel) {
        $this->setEngineModel($engineModel);
        $this->setThemeModel($themeModel);
    }

    /**
     * Sets the engine model
     * @param \ride\library\template\engine\EngineModel $engineModel
     * @return null
     */
    public function setEngineModel($engineModel) {
        $this->engineModel = $engineModel;
    }

    /**
     * Gets the template engine model
     * @return \ride\library\template\engine\EngineModel
     */
    public function getEngineModel() {
        return $this->engineModel;
    }

    /**
     * Sets the theme model
     * @param \ride\library\template\theme\ThemeModel $themeModel
     * @return null
     */
    public function setThemeModel(ThemeModel $themeModel) {
        $this->themeModel = $themeModel;

        $engines = $this->engineModel->getEngines();
        foreach ($engines as $engine) {
            $engine->setThemeModel($themeModel);
        }
    }

    /**
     * Gets the theme model
     * @return \ride\library\template\theme\ThemeModel
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
     * Gets the default template engine
     * @return string Machine name of the template engine
     */
    public function getDefaultEngine() {
        return $this->defaultEngine;
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
     * Gets the default theme
     * @return string Machine name of the theme
     */
    public function getDefaultTheme() {
        return $this->defaultTheme;
    }

    /**
     * Creates a new template
     * @param string $resource Resource name of the template
     * @param array $variables Variables for the template
     * @param string $theme Machine name of the template theme
     * @param string $engine Machine name of the template engine
     * @return \ride\library\template\Template
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
     * @param \ride\library\template\Template $template Template to
     * render
     * @return string Rendered template
     * @throws \ride\library\template\exception\ResourceNotSetException when
     * no resource was set to the template
     * @throws \ride\library\template\exception\ResourceNotFoundException when
     * the template could not be found by the engine
     */
    public function render(Template $template) {
        return $this->getTemplateEngine($template)->render($template);
    }

    /**
     * Gets the resource for the provided template instance
     * @param \ride\library\template\Template $template Template to get the
     * resource of
     * @return \ride\library\system\file\File $file File instance for the
     * template resource
     * @throws \ride\library\template\exception\ResourceNotFoundException
     */
    public function getFile(Template $template) {
        return $this->getTemplateEngine($template)->getFile($template);
    }

    /**
     * Gets the available resources for the provided namespace
     * @param string $namespace Namespace of the template
     * @param string $theme Machine name of the theme, instance will be set
     * after the call
     * @param string $engine Machine name of the engine, instance will be set
     * after the call
     * @return array
     */
    public function getFiles($namespace, &$theme = null, &$engine = null) {
        if (!$engine) {
            $engine = $this->defaultEngine;
        }

        if (!$theme) {
            $theme = $this->defaultTheme;
        }

        $engine = $this->getEngine($engine);

        return $engine->getFiles($namespace, $theme);
    }

    /**
     * Gets the meta from the template file.
     * The meta of a template file is defined in the first line of the template.
     * It should be a block comment line with semicolon seperated properties.
     * Property values are delimited from their keys with a colon.
     * eg {* name: Test; widget: my-widget; *}
     * @return array Array with the name of the property as key and the value of
     * the property as value
     */
    public function getTemplateMeta(Template $template) {
        $meta = array();

        $engine = $this->getTemplateEngine($template);
        $file = $engine->getFile($template);

        $contents = $file->read();
        if (!$contents) {
            return $meta;
        }

        $lines = explode("\n", $contents);
        $line = array_shift($lines);
        $line = trim($line);
        $lineLength = strlen($line);

        $blockComment = $engine->getBlockComment();
        $blockCommentOpen = $blockComment[0];
        $blockCommentOpenLength = strlen($blockCommentOpen);
        $blockCommentClose = $blockComment[1];
        $blockCommentCloseLength = strlen($blockCommentClose);

        if (strpos($line, $blockCommentOpen) !== 0 || strpos($line, $blockCommentClose) !== $lineLength - $blockCommentCloseLength) {
            return $meta;
        }

        $line = substr($line, $blockCommentOpenLength, $lineLength - $blockCommentOpenLength - $blockCommentCloseLength);
        $line = trim($line);

        $tokens = explode(';', $line);
        foreach ($tokens as $token) {
            $token = trim($token);
            if (!$token || strpos($token, ':') === false) {
                continue;
            }

            list($key, $value) = explode(':', $token, 2);
            $key = trim($key);
            $value = trim($value);

            $meta[$key] = $value;
        }

        return $meta;
    }

    /**
     * Gets the engine for the provided template
     * @param \ride\library\template\Template $template
     * @throws ResourceNotSetException
     * @return \ride\library\template\engine\Engine
     */
    protected function getTemplateEngine(Template $template) {
        $resource = $template->getResource();
        if (!$resource) {
            throw new ResourceNotSetException();
        }

        if ($template instanceof ThemedTemplate) {
            $theme = $this->getTheme($template->getTheme());
        } else {
            $theme = null;
        }

        return $this->getEngine($template->getEngine(), $theme);
    }

    /**
     * Gets the template theme
     * @param string $theme Machine name of theme theme
     * @return \ride\library\template\theme\Theme|null
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
     * @param \ride\library\template\theme\Theme $theme
     * @return \ride\library\template\engine\Engine
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
            $engines = $this->engineModel->getEngines();
            if (!$engines) {
                throw new TemplateException('Could not determine template engine: no engines set');
            }

            $engine = reset($engines);
            $engine = $engine->getName();
        }

        return $this->engineModel->getEngine($engine);
    }

}
