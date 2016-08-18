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
 * ModuleLoader class
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class ModuleLoader
{

    /**
     * @var array Modules added
     */
    private $modules = [];

    /**
     * @var \Tight\Tight Tight app
     */
    private $app;

    /**
     * Constructor
     * @param \Tight\Tight $app Tight app
     */
    public function __construct(\Tight\Tight $app) {
        $this->app = $app;
    }

    /**
     * Adds a new module
     * @param \Tight\Modules\AbstractModule $module Module
     * @throws \Tight\Exception\ModuleException If the module is already added
     */
    public function add(\Tight\Modules\AbstractModule $module) {
        if (!isset($this->modules[$module->getModuleName()])) {
            $this->modules[$module->getModuleName()] = $module;
            $module->onLoad();
        } else {
            throw new \Tight\Exception\ModuleException("Module <strong>" . $module->getModuleName() . "</strong> already exists");
        }
    }

    /**
     * Removes a module
     * @param string $moduleName Module name
     * @return boolean TRUE on success, FALSE if the module does not exists
     */
    public function remove($moduleName) {
        if (isset($this->modules[$moduleName])) {
            $this->modules[$moduleName]->onRemove();
            unset($this->modules[$moduleName]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Gets all the modules added
     * @return array Modules
     */
    public function getModules() {
        return $this->modules;
    }

    /**
     * Gets a single module
     * @param string $moduleName Module name
     * @return \Tight\Module\AbstractModule Module or null if the module name 
     * cant be found
     */
    public function getModule($moduleName) {
        if (isset($this->modules[$moduleName])) {
            return $this->modules[$moduleName];
        } else {
            return null;
        }
    }

}
