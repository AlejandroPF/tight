<?php

namespace Tight;

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

/**
 * Tight framework main class
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class Tight
{

    /**
     * Current Version
     */
    const VERSION = "v1.1.0-dev";

    private static $INSTANCE = null;

    /**
     *
     * @var \Tight\TightConfig Configuration
     */
    private $config = null;

    /**
     * @var \Tight\Router Router
     */
    private $router;

    /**
     * @var \Smarty Smarty templates system
     */
    private $smarty;

    /**
     * @var \Tight\Modules\ModuleLoader Module loader
     */
    private $moduleLoader;

    /**
     * Creates an instance of Tight Framework.
     * @param array|TightConfig $config Settings to override the config file
     */
    public function __construct($config = []) {
        // Sets the instance
        self::$INSTANCE = $this;
        set_exception_handler([$this, "exceptionHandler"]);
        $this->setConfig($config);
        $this->router = new Router($this->config->basePath);
        $this->smarty = new \Smarty;
        $this->smarty->template_dir = $this->config->smarty["template_dir"];
        $this->smarty->compile_dir = $this->config->smarty["compile_dir"];
        $this->smarty->config_dir = $this->config->smarty["config_dir"];
        $this->smarty->cache_dir = $this->config->smarty["cache_dir"];
        $this->moduleLoader = new \Tight\Modules\ModuleLoader($this);
    }

    /**
     * Gets the class instance
     * @return \Tight\Tight
     */
    public static function getInstance() {
        if (null !== self::$INSTANCE) {
            return self::$INSTANCE;
        } else {
            return new \Tight\Tight;
        }
    }

    /**
     * Sets the configuration
     * @param array|\Tight\TightConfig $config Configuration object or 
     * associative array with available options
     * @throws \InvalidArgumentException If $config is not an array or an 
     * instance of \Tight\TightConfig class
     */
    public function setConfig($config) {
        if (is_array($config)) {
            $config = new \Tight\TightConfig($config);
        } elseif (!$config instanceof \Tight\TightConfig) {
            throw new \InvalidArgumentException("Argument passed to Tight::__constructor must be array or <b>\Tight\TightConfig</b> object");
        }
        $this->config = $config;
    }

    /**
     * Gets the configuration
     * @return \Tight\TightConfig
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Gets the Smarty
     * @return \Smarty
     */
    public function getSmarty() {
        return $this->smarty;
    }

    /**
     * Custom exception handler
     * @param \Exception $exception Exception
     */
    public function exceptionHandler($exception) {
        self::printException($exception);
    }

    /**
     * Custom exception handler
     * @param \Exception $exception Exception
     * @codeCoverageIgnore
     */
    public static function printException($exception) {
        $lastTrace = $exception->getTrace()[count($exception->getTrace()) - 1];
        $output = <<<EXC
                <!DOCTYPE>
                <html>
                    <head>
                        <title>Tight Framework Exception</title>
                        <style>
                        *{
                            margin: 0;
                            padding: 0;
                        }
                        html {
                            font-family: Helvetica;
                        }
                        body {
                            padding: 1em;
                        }
                        h1 {
                            margin-bottom: 0.5em;
                        }
                        p {
                            margin-top: 0.25em;
                            margin-bottom: 0.5em;
                        }
                        .padding-left {
                            padding-left: 2em;
                        }
                        </style>
                    </head>
                    <body>
                        <h1>Tight Framework Exception</h1>
EXC;
        $output .= "<p><strong>" . get_class($exception) . ": </strong>" . $exception->getMessage() . "</p>";
        $output .= "<p class='padding-left'>in <strong>" . $lastTrace['file'] . "</strong> at line <strong>" . $lastTrace['line'] . "</strong></p>";
        $output .= "<br/>";
        $output .= "<p>Stack Trace:</p>";
        $trace = $exception->getTrace();
        $size = count($trace);
        for ($index = 0; $index < $size; $index++) {
            $element = $trace[$index];
            $class = isset($element["class"]) ? $element["class"] : "";
            $type = isset($element["type"]) ? $element["type"] : "";
            $func = isset($element["function"]) ? $element["function"] : "";
            $file = isset($element["file"]) ? "at <strong>" . $element["file"] . "</strong>" : "";
            $line = isset($element["line"]) ? " at line <strong>" . $element["line"] . "</strong>" : "";
            $output .= "<p>#" . ($index + 1) . ": " . $class . $type . $func . "() " . $file . $line . "</strong></p>";
        }
        echo $output;
    }

    /**
     * Gets the router
     * @return \Tight\Router
     */
    public function getRouter() {
        return $this->router;
    }

    /**
     * Runs the router to send response to the request URI
     */
    public function run() {
        if ($this->config->mvc["enableRouter"]) {
            $this->router->runMvc();
        } else {
            $this->router->run();
        }
    }

    public function getModuleLoader() {
        return $this->moduleLoader;
    }

    public function moduleAdd(\Tight\Modules\AbstractModule $module) {
        $this->moduleLoader->add($module);
    }

    public function moduleRemove($moduleName) {
        return $this->moduleLoader->remove($moduleName);
    }

    /**
     * Gets all the modules added
     * @return array Modules
     */
    public function getModules() {
        return $this->moduleLoader->getModules();
    }

    /**
     * Gets a single module
     * @param string $moduleName Module name
     * @return \Tight\Module\AbstractModule Module or null if the module name 
     * cant be found
     */
    public function getModule($moduleName) {
        return $this->moduleLoader->getModule($moduleName);
    }

}
