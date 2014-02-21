<?php

namespace ride\library\template\theme;

/**
 * Model of the available themes
 */
interface ThemeModel {

    /**
     * Gets a theme
     * @param string $name Machine name of the theme
     * @return Theme
     * @throws ride\library\template\exception\ThemeNotFoundException
     */
    public function getTheme($name);

    /**
     * Gets the available themes
     * @return array Array with the machine name of the theme as key and an
     * instance of Theme as value
     */
    public function getThemes();

}