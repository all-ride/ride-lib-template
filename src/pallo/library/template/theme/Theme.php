<?php

namespace pallo\library\template\theme;

/**
 * Interface for a template theme
 */
interface Theme {

    /**
     * Gets the machine name of the theme
     * @return string
     */
    public function getName();

    /**
     * Gets the parent theme
     * @return string Machine name of the parent theme
     */
    public function getParent();

    /**
     * Gets the machine name(s) of the available template engines
     * @return string|array
     */
    public function getEngines();

}