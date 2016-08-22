<?php

/*
 * The MIT License
 *
 * Copyright 2016 Alejandro Peña Florentín (alejandropenaflorentin@gmail.com).
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Tight\Modules;

/**
 * AbstractModule class for creating Tight Framework modules
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
abstract class AbstractModule
{

    /**
     * @var string Module name
     */
    private $moduleName;

    /**
     * @var string Module version
     */
    private $version;

    /**
     * @var \Tight\TightConfig Application settings
     */
    private $appConfig;

    /**
     * @var \Tight\BaseConfig Module configuration
     */
    private $moduleConfig;

    public function __construct($name, $version = "v1.0") {
        $this->moduleName = $name;
        $this->version = $version;
        $app = \Tight\Tight::getInstance();
        $this->appConfig = $app->getConfig();
    }

    public function getModuleVersion() {
        return $this->version;
    }

    /**
     * Gets the module name
     * @return string Module name
     */
    public function getModuleName() {
        return $this->moduleName;
    }

    /**
     * Sets the module configuration
     * @param \Tight\BaseConfig $conf Configuration
     * @return \Tight\Modules\AbstractModule Fluent setter
     */
    public function setConfig(\Tight\BaseConfig $conf) {
        $this->moduleConfig = $conf;
        $this->onConfigChange();
        return $this;
    }

    /**
     * Gets the module configuration
     * @return \Tight\BaseConfig Config
     */
    public function getConfig() {
        return $this->moduleConfig;
    }

    /**
     * Gets the application configuration
     * @return \Tight\TightConfig Tight app config
     */
    public function getAppConfig() {
        return $this->appConfig;
    }

    abstract public function onConfigChange();

    abstract public function onLoad();

    abstract public function onRemove();
}
