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
 * Router class for creating http route methods defined by patterns
 * 
 * MIDDLEWARE
 * 
 *  When creating routes you can set 1 or more middleware that will be executed
 * before the route callable.
 *  You can set route middleware by adding callables <b>before</b> the last 
 * parameter, which is the route callable, for the following class methods:
 * <ul>
 *  <li>Tight\Router::get,</li>
 *  <li>Tight\Router::post, </li>
 *  <li>Tight\Router::update,</li>
 *  <li>Tight\Router::delete and</li>
 *  <li>Tight\Router::map.</li>
 * </ul>
 * Eg.
 * <pre>
 * $router->get("/",'userDefinedFunction',function(){
 *      return "Hello World!";
 * });
 * </pre>
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class Router
{

    /**
     * @var array Routes
     */
    protected $routes;

    /**
     * @var string Base path for rutes. If Router is not instanciated in 
     * document root this value must be set
     */
    private $basePath;

    /**
     * @var array Array of callables for error handling
     */
    private $errorHandler = [
        "notFound" => null, // 404
    ];

    /**
     * Creates a new instance
     * @param string $basePath Base path used when creating the routes.If Router
     *  is not instanciated in server document root this value must be set
     */
    public function __construct($basePath) {
        if (null !== $basePath && is_string($basePath) && !empty($basePath)) {
            $basePath = Utils::filterPath($basePath);
            if (Utils::inString($basePath, $_SERVER['DOCUMENT_ROOT'])) {
                $this->basePath = Utils::removeSubstring($basePath, $_SERVER['DOCUMENT_ROOT']);
            } else {
                $this->basePath = $basePath;
            }
        } else {
            $this->basePath = "/";
        }
        $this->errorHandler = [
            /**
             * @codeCoverageIgnore
             */
            "notFound" => function() {
                return "Page not found";
            }
        ];
    }

    /**
     * Creates a new route using GET method
     * 
     * This method need at least 2 arguments: route pattern and callable.
     * You can create midleware callables which will be executed before the route
     * callable. 
     * @return \Tight\Router Fluent method
     */
    public function get() {

        return $this->url(Route::METHOD_GET, func_get_args());
    }

    /**
     * Creates a new route using POST method
     * 
     * This method need at least 2 arguments: route pattern and callable.
     * You can create midleware callables which will be executed before the route
     * @return \Tight\Router Fluent method
     */
    public function post() {
        return $this->url(Route::METHOD_POST, func_get_args());
    }

    /**
     * Creates a new route using UPDATE method
     * 
     * This method need at least 2 arguments: route pattern and callable.
     * You can create midleware callables which will be executed before the route
     * callable. 
     * @return \Tight\Router Fluent method
     */
    public function update() {
        return $this->url(Route::METHOD_UPDATE, func_get_args());
    }

    /**
     * Creates a new route using DELETE method
     * 
     * This method need at least 2 arguments: route pattern and callable.
     * You can create midleware callables which will be executed before the route
     * callable. 
     * @return \Tight\Router Fluent method
     */
    public function delete() {
        return $this->url(Route::METHOD_DELETE, func_get_args());
    }

    /**
     * Creates a new route for the given methods
     * 
     * This method need at least 3 arguments: method or array of methods, route pattern and callable.
     * You can create midleware callables which will be executed before the route
     * callable. 
     * @return \Tight\Router Fluent method
     */
    public function map() {
        $args = func_get_args();
        $methods = array_shift($args);
        return $this->url($methods, $args);
    }

    /**
     * Creates a new route
     * @param array|string $methods Allowed methods for the route
     * @param array $args Array of a minimum size of 2 indexes: pattern and callable
     * @return \Tight\Router Fluent method
     */
    private function url($methods, $args) {
        if (is_string($methods)) {
            $methods = [$methods];
        }
        $pattern = array_shift($args); // 1st index-> Pattern
        $callable = array_pop($args); // Last index-> callable
        $route = new Route(Utils::removeDouble($this->basePath . $pattern, "/"), $callable);
        $route->setHttpMethods($methods);
        if (count($args) > 0) {
            // Adds the middleware
            $route->setMiddleware($args);
        }
        $this->routes[] = $route;
        return $this;
    }

    /**
     * Run the router.
     * 
     * This method checks the current uri and searches any matched pattern created
     * as a route.
     * 
     * This method must be called the last
     * @codeCoverageIgnore
     */
    public function run() {
        $pattern = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        echo $this->dispatch($pattern, $method);
    }

    /**
     * Method called when route cant be found
     * @param \Closure $callback Callback function for not found handler
     */
    public function notFound($callback) {
        $this->errorHandler['notFound'] = $callback;
    }

    private function dispatchNotFound() {
        return call_user_func($this->errorHandler["notFound"]);
    }

    public function dispatch($pattern, $method) {
        $output = null;
        $found = null;
        $index = 0;
        $size = count($this->routes);
        while ($index < $size && null === $found) {
            if ($this->routes[$index]->match($pattern) && in_array(strtolower($method), $this->routes[$index]->getHttpMethods())) {
                $found = $this->routes[$index];
            }
            $index++;
        }
        if (null !== $found && in_array(strtolower($method), $found->getHttpMethods())) {
            $output = $found->dispatch();
        } else {
            $output = $this->dispatchNotFound();
        }
        return $output;
    }

    /**
     * Gets all routes
     * @return array Array of Tight\Route
     */
    public function getRoutes() {
        return $this->routes;
    }

    /**
     * Gets the request URN relative to Tight\Router::basePath
     * 
     * <b>NOTE</b>
     * <ul>
     * <li><b>URL: </b> http://example.com/</li>
     * <li><b>URN: </b> /path/to/file.html</li>
     * <li><b>URI: </b> URL + URN
     * </ul>
     * @return string Request URN
     */
    public function getRequestUrn() {
        $output = Utils::addTrailingSlash("/" . Utils::removeSubstring($_SERVER['REQUEST_URI'], $this->basePath));
        return $output;
    }

    /**
     * Method used for autoload classes
     * @param string $className Class name
     * @throws \Tight\Exception\FileNotFoundException If directories or files cant be found
     */
    private function registerClasses($className) {
        $config = \Tight\Tight::getInstance()->getConfig();
        $directoryNotFound = false;
        $fileNotFound = false;
        $controllerDir = $config->mvc["controller_dir"];
        $modelDir = $config->mvc["model_dir"];
        $viewDir = $config->mvc["view_dir"];
        if (is_dir($controllerDir) && is_dir($modelDir) && is_dir($viewDir)) {
            if (strpos(strtolower($className), "controller") !== FALSE) {
                if (is_file($controllerDir . $className . ".php")) {
                    require_once $controllerDir . $className . ".php";
                } else {
                    $fileNotFound = $controllerDir . $className . ".php";
                }
            } elseif (strpos(strtolower($className), "model") !== FALSE) {
                if (is_file($modelDir . $className . ".php")) {
                    require_once $modelDir . $className . ".php";
                } else {
                    $fileNotFound = $modelDir . $className . ".php";
                }
            } elseif (strpos(strtolower($className), "view") !== FALSE) {
                if (is_file($viewDir . $className . ".php")) {
                    require_once $viewDir . $className . ".php";
                } else {
                    $fileNotFound = $viewDir . $className . ".php";
                }
            }
        } else {
            $directoryNotFound = true;
        }
        if ($directoryNotFound) {
            $err = !is_dir($controllerDir) ? "Controller directory not found" : !is_dir($modelDir) ? "Model directory not found" : "View directory not found";
            throw new \Tight\Exception\NotFoundException($err);
        } else if ($fileNotFound !== false) {           
            $err = "File <strong>" . $fileNotFound . "</strong> not found";
            throw new \Tight\Exception\NotFoundException($err);
        }
    }

    public function runMvc() {
        try {
            $requestUrn = $this->getRequestUrn();
            spl_autoload_register(array($this, "registerClasses"), TRUE, TRUE);
            $name = "";
            $method = null;
            $args = [];
            if ("/" == $requestUrn) {
                $name = \Tight\Tight::getInstance()->getConfig()->mvc["indexName"];
            } else {
                $requestUrn = trim($requestUrn, "/");
                $explode = explode("/", $requestUrn);
                $name = array_shift($explode);
                if (count($explode) > 0) {
                    $method = array_shift($explode);
                    if (count($explode) > 0) {
                        $args = $explode;
                    }
                }
            }
            $name = ucwords($name);
            $contName = $name . "Controller";
            $viewName = $name . "View";
            $modName = $name . "Model";
            $model = new $modName();
            $view = new $viewName();
            $controller = new $contName($model, $view);
            if ("" !== $method && !empty($method)) {
                if (method_exists($controller, $method)) {
                    call_user_method_array($method, $controller, $args);
                } else {
                    throw new \Tight\Exception\RouterException("Method <strong>" . $method . "</strong> not defined for <strong>" . $contName . "</strong> class");
                }
            }
            $controller->render();
        } catch (\Tight\Exception\NotFoundException $ex) {
            echo $this->dispatchNotFound();
        } catch (\SmartyException $ex) {
            echo $this->dispatchNotFound();
        } catch (\Exception $ex) {
            \Tight\Tight::printException($ex);
        }
    }

}
