<?php

namespace ride\library\template\engine;

/**
 * Model of the available template engines
 */
interface EngineModel {

    /**
     * Gets a template engine
     * @param string $name Machine name of the template engine
     * @return Engine
     * @throws ride\library\template\exception\TemplateEngineNotFoundException
     */
    public function getEngine($name);

    /**
     * Gets the available themes
     * @return array Array with the machine name of the theme as key and an
     * instance of Theme as value
     */
    public function getEngines();

}