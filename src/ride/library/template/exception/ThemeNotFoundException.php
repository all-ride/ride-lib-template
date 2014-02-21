<?php

namespace ride\library\template\exception;

/**
 * Exception when a theme does not exist
 */
class ThemeNotFoundException extends TemplateException {

    /**
     * Name to the requested theme
     * @var string
     */
    private $theme;

    /**
     * Constructs a new theme not found exception
     * @param string $theme Name of the requested theme
     * @param Exception $previous previous exception causing this one
     * @return null
     */
    public function __construct($theme, $previous = null) {
        parent::__construct('Theme not found: ' . $theme, 0, $previous);

        $this->theme = $theme;
    }

    /**
     * Gets the name of the requested theme
     * @return string
     */
    public function getTheme() {
        return $this->theme;
    }

}