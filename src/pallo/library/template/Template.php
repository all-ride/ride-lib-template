<?php

namespace pallo\library\template;

/**
 * Interface for a template
 */
interface Template {

    /**
     * Gets the template resource
     * @return string Reference to the template resource
     */
    public function getResource();

    /**
     * Gets the template engine
     * @return string Machine name of the template engine
     */
    public function getEngine();

    /**
     * Gets the variables of the template
     * @return array
     */
    public function getVariables();

    /**
     * Sets a variable to the template
     * @param string|array $key Name of the variable or an array with of
     * key-value pairs to set multiple variables at once
     * @param mixed $value Value of the variable
     * @return null
     */
    public function set($key, $value = null);

    /**
     * Gets a variable from the template
     * @param string $key Name of the variable
     * @param mixed $default Default value for when the variable is not set
     * @return mixed Value of the variable if set, the provided default value
     * otherwise
     */
    public function get($key, $default = null);

}