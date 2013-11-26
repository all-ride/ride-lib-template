<?php

namespace pallo\library\template\exception;

/**
 * Exception when a template engine does not exist
 */
class TemplateEngineNotFoundException extends TemplateException {

    /**
     * Name to the requested template engine
     * @var string
     */
    private $engine;

    /**
     * Constructs a new template engine not found exception
     * @param string $engine Name of the requested template engine
     * @param Exception $previous previous exception causing this one
     * @return null
     */
    public function __construct($engine, $previous = null) {
        parent::__construct('Template engine not found: ' . $engine, 0, $previous);

        $this->engine = $engine;
    }

    /**
     * Gets the name of the requested template engine
     * @return string
     */
    public function getEngine() {
        return $this->engine;
    }

}