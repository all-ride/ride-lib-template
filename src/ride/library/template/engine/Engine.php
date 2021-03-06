<?php

namespace ride\library\template\engine;

use ride\library\template\theme\ThemeModel;
use ride\library\template\Template;

/**
 * Interface for a template engine
 */
interface Engine {

    /**
     * Gets the name of this template engine
     * @return string
     */
    public function getName();

    /**
     * Gets the extension for the template resources
     * @return string
     */
    public function getExtension();

    /**
     * Gets the block comment delimiters
     * @return array Array with the open tag as first element and the close tag
     * as second element
     */
    public function getBlockComment();

    /**
     * Sets the theme model to this template engine
     * @param \ride\library\template\theme\ThemeModel
     * @return null
     */
    public function setThemeModel(ThemeModel $themeModel);

    /**
     * Gets the themes which support this engine
     * @return array Array with the name of the theme as key and the true as
     * value
     */
    public function getThemes();

    /**
     * Creates a template for this engine
     * @param string $resource Resource to the template
     * @param array $variables Variables for the template
     * @return Template
     */
    public function createTemplate($resource, array $variables = null);

    /**
     * Renders a template
     * @param \ride\library\template\Template $template Template to render
     * @return string Rendered template
     * @throws \ride\library\template\exception\ResourceNotSetException when
     * no template was set to the template
     * @throws \ride\library\template\exception\ResourceNotFoundException when
     * the template could not be found by the engine
     */
    public function render(Template $template);

    /**
     * Gets the template resource
     * @param \ride\library\template\Template $template Template to get the
     * resource of
     * @return \ride\library\system\file\File $file File instance for the
     * template resource
     * @throws \ride\library\template\exception\ResourceNotSetException when
     * no template was set to the template
     * @throws \ride\library\template\exception\ResourceNotFoundException when
     * the template could not be found by the engine
     */
    public function getFile(Template $template);

    /**
     * Gets the available template resources
     * @param string $namespace
     * @param string $theme
     * @return array Array with the relative path of the resource as key and the
     * name relative to the namespace as value
     */
    public function getFiles($namespace, $theme = null);

}
