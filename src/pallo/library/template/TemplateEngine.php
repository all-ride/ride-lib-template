<?php

namespace pallo\library\template;

/**
 * Interface for a template engine
 */
interface TemplateEngine {

    /**
     * Gets the name of this template engine
     * @return string
     */
    public function getName();

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
     * @throws pallo\library\template\exception\TemplateNotSetException when
     * no template was set to the template
     * @throws pallo\library\template\exception\TemplateNotFoundException when
     * the template could not be found by the engine
     */
    public function render(Template $template);

}