<?php

namespace ride\library\template;

/**
 * Interface for a themed template
 */
interface ThemedTemplate extends Template {

    /**
     * Gets the template resource id
     * @return string|null Template resource id
     */
    public function getResourceId();

    /**
     * Gets the theme
     * @return string Machine name of the theme
     */
    public function getTheme();

}