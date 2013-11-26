<?php

namespace pallo\library\template\engine;

use pallo\library\template\Template;

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
     * @param pallo\library\template\Template $template Template to render
     * @return string Rendered template
     * @throws pallo\library\template\exception\ResourceNotSetException when
     * no template was set to the template
     * @throws pallo\library\template\exception\ResourceNotFoundException when
     * the template could not be found by the engine
     */
    public function render(Template $template);

}