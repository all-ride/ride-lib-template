<?php

namespace ride\library\template\exception;

/**
 * Exception when a the template resource is not set to the template
 */
class ResourceNotSetException extends TemplateException {

    /**
     * Constructs a new resource not set exception
     * @param Exception $previous Previous exception causing this one
     * @return null
     */
    public function __construct($previous = null) {
        parent::__construct('Template resource not set', 0, $previous);
    }

}