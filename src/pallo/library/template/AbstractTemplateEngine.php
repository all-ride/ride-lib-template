<?php

namespace pallo\library\template;

/**
 * Abstract implementation for a template engine
 */
abstract class AbstractTemplateEngine implements TemplateEngine {

    /**
     * Gets the name of this template engine
     * @return string
     */
    public function getName() {
        return static::NAME;
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
     * @return pallo\library\template\Template
     */
    protected function createTemplateInstance() {
        return new GenericTemplate();
    }

}