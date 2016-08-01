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
 * Description of Route
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class Route
{

    const METHOD_GET = "get";
    const METHOD_POST = "post";
    const METHOD_UPDATE = "update";
    const METHOD_DELETE = "delete";
    const PARAMETER_CHARACTER = ":";

    protected $methods = [];

    /**
     * @var array Key-value array of URL parameters
     */
    protected $params = [];

    /**
     * @var array[Callable] Middleware to be run before only this route instance
     */
    protected $middleware = [];

    /**
     *
     * @var string The route pattern (e.g. "/books/:id")
     */
    protected $pattern = "";
    protected $callable;
    private $argsPattern = self::PARAMETER_CHARACTER . "(\\w+)";

    public function __construct($pattern, $callable) {
        $this->setPattern($pattern);
        $this->setCallable($callable);
        $this->filterPattern();
    }

    private function filterPattern() {
        $pattern = $this->getPattern();
        $match = [];
        $pattern = str_replace("/", "\\/", $pattern);
        if (preg_match_all("/" . $this->argsPattern . "/", $pattern, $match)) {
            $strings = $match[0];
            $parameters = $match[1];
            $this->params = $parameters;
            $pattern = str_replace($strings, str_replace(self::PARAMETER_CHARACTER, "", $this->argsPattern), $pattern);
        }
        $this->setPattern("/^" . $pattern . "$/");
    }

    public function setPattern($pattern) {
        if (!is_string($pattern)) {
            throw new \InvalidArgumentException('Route name must be a string');
        }
        $this->pattern = $pattern;
        return $this;
    }

    public function setCallable($callback) {
        if (is_callable($callback))
            $this->callable = $callback;
    }

    /**
     * Add supported HTTP method(s)
     */
    public function setHttpMethods($methods) {
        $this->methods = $methods;
    }

    /**
     * Get middleware
     * @return array[Callable]
     */
    public function getMiddleware() {
        return $this->middleware;
    }

    /**
     * Set middleware
     *
     * This method allows middleware to be assigned to a specific Route.
     * If the method argument `is_callable` (including callable arrays!),
     * we directly append the argument to `$this->middleware`. Else, we
     * assume the argument is an array of callables and merge the array
     * with `$this->middleware`.  Each middleware is checked for is_callable()
     * and an InvalidArgumentException is thrown immediately if it isn't.
     *
     * @param  Callable|array
     * @return \Tight\Route
     * @throws \InvalidArgumentException If argument is not callable or not an array of callables.
     */
    public function setMiddleware($middleware) {
        if (is_callable($middleware)) {
            $this->middleware[] = $middleware;
        } elseif (is_array($middleware)) {
            foreach ($middleware as $callable) {
                if (!is_callable($callable)) {
                    throw new \InvalidArgumentException('All Route middleware must be callable');
                }
            }
            $this->middleware = array_merge($this->middleware, $middleware);
        } else {
            throw new \InvalidArgumentException('Route middleware must be callable or an array of callables');
        }

        return $this;
    }

    /**
     * Get supported HTTP methods
     * @return array
     */
    public function getHttpMethods() {
        return $this->methods;
    }

    /**
     * Get the pattern
     * @return string Pattern
     */
    public function getPattern() {
        return $this->pattern;
    }

    /**
     * Append supported HTTP methods
     */
    public function appendHttpMethods() {
        $args = func_get_args();
        $this->methods = array_merge($this->methods, $args);
    }

    /**
     * Get route parameters
     * @return array Route parameters
     */
    public function getParams() {
        return $this->params;
    }

    public function match($uri) {
        $matches = [];
        if (preg_match_all($this->pattern, $uri, $matches)) {
            $params = $this->params;
            // Remove 1st match, the full string
            unset($matches[0]);
            // Fix array index
            $matches = array_values($matches);
            // Clear the array parameter
            $this->params = [];
            for ($index = 0; $index < count($params); $index++) {
                $name = $params[$index];
                $value = $matches[$index][0];
                // Set parameter
                $this->params[$name] = $value;
            }
            return true;
        }
        return false;
    }

    /**
     * Dispatch route
     *
     * This method invokes the route object's callable. If middleware is
     * registered for the route, each callable middleware is invoked in
     * the order specified.
     *
     * @return bool
     */
    public function dispatch() {
        foreach ($this->middleware as $mw) {
            call_user_func_array($mw, array($this));
        }
        $return = call_user_func_array($this->callable, array_values($this->getParams()));
        return $return;
    }

}
