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
    const VERSION = "v1.0.1";

    private static $INSTANCE = null;

    /**
     *
     * @var Tight\TightConfig Configuration
     */
    private $config = null;

    /**
     *
     * @var Tight\Route\Router 
     */
    private $router;
    private $smarty;

    /**
     * Creates an instance of Tight Framework.
     * @param array $config Settings to override the config file
     */
    public function __construct($config = []) {
        set_exception_handler([$this, "exceptionHandler"]);
        $this->setConfig($config);
        $this->router = new Router($this->config->basePath);
        $this->smarty = new \Smarty;
        $this->smarty->template_dir = $this->config->smarty["template_dir"];
        $this->smarty->compile_dir = $this->config->smarty["compile_dir"];
        $this->smarty->config_dir = $this->config->smarty["config_dir"];
        $this->smarty->cache_dir = $this->config->smarty["cache_dir"];
    }

    public static function getInstance() {
        if (null !== self::$INSTANCE) {
            return self::$INSTANCE;
        } else {
            return new \Tight\Tight;
        }
    }

    public function setConfig($config) {
        if (is_array($config)) {
            $config = new \Tight\TightConfig($config);
        } elseif (!$config instanceof \Tight\TightConfig) {
            throw new \InvalidArgumentException("Argument passed to Tight::__constructor must be array or <b>\Tight\TightConfig</b> object");
        }
        $this->config = $config;
    }

    public function getConfig() {
        return $this->config;
    }

    public function getSmarty() {
        return $this->smarty;
    }

    /**
     * Custom exception handler
     * @param \Exception $ex Exception
     * @codeCoverageIgnore
     */
    public function exceptionHandler($ex) {
        $lastTrace = $ex->getTrace()[count($ex->getTrace()) - 1];
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
        $output .= "<p><strong>" . get_class($ex) . ": </strong>" . $ex->getMessage() . "</p>";
        $output .= "<p class='padding-left'>in <strong>" . $lastTrace['file'] . "</strong> at line <strong>" . $lastTrace['line'] . "</strong></p>";
        $output .= "<br/>";
        $output .= "<p>Stack Trace:</p>";
        $trace = $ex->getTrace();
        for ($index = 0; $index < count($trace); $index++) {
            $el = $trace[$index];
            $output .= "<p>#" . ($index + 1) . ": " . $el['class'] . $el['type'] . $el['function'] . "() at <strong>" . $el['file'] . "</strong> at line <strong>" . $el['line'] . "</strong></p>";
        }
        echo $output;
    }

    /**
     * 
     * @return \Tight\Router
     */
    public function getRouter() {
        return $this->router;
    }

}
