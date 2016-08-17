<?php

/*
 * The MIT License
 *
 * Copyright 2016 Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
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

namespace Tight\Mvc;

/**
 * AbstractModel class
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
abstract class AbstractModel
{

    /**
     * @var \Tight\Tight Tight App
     */
    private $app;

    /**
     * @var array Variables
     */
    private $vars;

    public function __construct() {
        $this->app = \Tight\Tight::getInstance();
    }

    /**
     * Sets a variable
     * @param string $key Variable name
     * @param mixed $value Variable value
     * @return \Tight\Mvc\AbstractModel Fluent setter
     */
    public function set($key, $value) {
        $this->vars[$key] = $value;
        return $this;
    }

    /**
     * Gets a defined variable
     * @param string $key Variable name
     * @return mixed Variable value or null if variable name cant be found
     */
    public function get($key) {
        return isset($this->vars[$key]) ? $this->vars[$key] : null;
    }

    /**
     * Gets all defined variables
     * @return array Variables
     */
    public function getVars() {
        return $this->vars;
    }

    /**
     * Gets the Tight App
     * @return \Tight\Tight
     */
    public function getApp() {
        return $this->app;
    }

}
