<?php

namespace ride\library\template;

/**
 * Generic implementation for a template
 */
class GenericTemplate implements Template {

    /**
     * Reference to the template resource
     * @var string
     */
    protected $resource;

    /**
     * Machine name of the template engine
     * @var string
     */
    protected $engine;

    /**
     * Variables for the template
     * @var array
     */
    protected $variables = array();

    /**
     * Sets the template resource
     * @param string $resource Reference to the template resource
     * @return null
     */
    public function setResource($resource) {
        $this->resource = $resource;
    }

    /**
     * Gets the template resource
     * @return string Reference to the template resource
     */
    public function getResource() {
        return $this->resource;
    }

    /**
     * Sets the template engine
     * @param string $engine Machine name of the template engine
     * @return null
     */
    public function setEngine($engine) {
        $this->engine = $engine;
    }

    /**
     * Gets the template engine
     * @return string Machine name of the template engine
     */
    public function getEngine() {
        return $this->engine;
    }

    /**
     * Gets the variables of the template
     * @return array
     */
    public function getVariables() {
        return $this->variables;
    }

    /**
     * Sets a variable to the template
     * @param string|array $key Name of the variable or an array with of
     * key-value pairs to set multiple variables at once
     * @param mixed $value Value of the variable
     * @return null
     */
    public function set($key, $value = null) {
        if (!is_array($key)) {
            $this->variables[$key] = $value;
        } else {
            foreach ($key as $index => $value) {
                $this->variables[$index] = $value;
            }
        }
    }

    /**
     * Gets a variable from the template
     * @param string $key Name of the variable
     * @param mixed $default Default value for when the variable is not set
     * @return mixed Value of the variable if set, the provided default value
     * otherwise
     */
    public function get($key, $default = null) {
        if (!isset($this->variables[$key])) {
            return $default;
        }

        return $this->variables[$key];
    }

}