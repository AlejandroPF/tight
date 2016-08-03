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
 *      echo "Hello World!";
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
                echo "Page not found";
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
     * @return type
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
        $this->dispatch($pattern, $method);
    }

    /**
     * Method called when route cant be found
     */
    public function notFound($callback) {
        $this->errorHandler['notFound'] = $callback;
    }

    public function dispatch($pattern, $method) {
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
            $found->dispatch();
        } else {
            call_user_func($this->errorHandler["notFound"]);
        }
    }

    public function getRoutes() {
        return $this->routes;
    }

}
