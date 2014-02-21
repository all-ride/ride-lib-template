<?php

namespace ride\library\template\exception;

/**
 * Exception when a requested template resource does not exist
 */
class ResourceNotFoundException extends TemplateException {

    /**
     * Reference to the requested template resource
     * @var string
     */
    private $resource;

    /**
     * Constructs a new resource not found exception
     * @param string $resource Reference to the requested template resource
     * @param Exception $previous previous exception causing this one
     * @return null
     */
    public function __construct($resource, $previous = null) {
        parent::__construct('Template resource not found: ' . $resource, 0, $previous);

        $this->resource = $resource;
    }

    /**
     * Gets the resource of the requested template
     * @return string
     */
    public function getTemplateResource() {
        return $this->resource;
    }

}