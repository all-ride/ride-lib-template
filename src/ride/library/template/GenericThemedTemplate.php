<?php

namespace ride\library\template;

/**
 * Generic implementation for a template
 */
class GenericThemedTemplate extends GenericTemplate implements ThemedTemplate {

    /**
     * Id of the template resource
     * @var string
     */
    protected $resourceId;

    /**
     * Machine name of the theme
     * @var string
     */
    protected $theme;

    /**
     * Sets the template resource id
     * @param string $resource Template resource id
     * @return null
     */
    public function setResourceId($resourceId) {
        $this->resourceId = $resourceId;
    }

    /**
     * Gets the template resource id
     * @return string|null Template resource id
     */
    public function getResourceId() {
        return $this->resourceId;
    }

    /**
     * Sets the template theme
     * @param string $theme Machine name of the template theme
     * @return null
     */
    public function setTheme($theme) {
        $this->theme = $theme;
    }

    /**
     * Gets the template theme
     * @return string Machine name of the template theme
     */
    public function getTheme() {
        return $this->theme;
    }

}