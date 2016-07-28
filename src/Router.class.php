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
class Router
{

    /**
     * @var array 
     */
    protected $routes;

    /**
     * 
     * @param type $pattern
     * @param type $args
     * @return 
     */
    public function get() {

        return $this->url(Route::METHOD_GET, func_get_args());
    }

    public function post() {
        return $this->url(Route::METHOD_POST, func_get_args());
    }

    public function update() {
        return $this->url(Route::METHOD_DELETE, func_get_args());
    }

    public function delete() {
        return $this->url(Route::METHOD_UPDATE, func_get_args());
    }

    public function url($methods, $args) {
        if (is_string($methods))
            $methods = [$methods];

        $pattern = array_shift($args); // 2nd-> Pattern
        $callable = array_pop($args); // Last -> callable
        $route = new Route($pattern, $callable);
        $route->setHttpMethods($methods);
        if (count($args) > 0)
            $route->setMiddleware($args);
        $this->routes[] = $route;
        return $this;
    }

    public function run() {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $found = null;
        $index = 0;
        while ($index < count($this->routes) && null === $found) {
            if ($this->routes[$index]->match($uri)) {
                $found = $this->routes[$index];
            }
            $index++;
        }
        if (null !== $found && in_array(strtolower($method), $found->getHttpMethods())) {
            $found->dispatch();
        } else {
            $this->notFound();
        }
    }

    public function notFound() {
        echo "The requested URL cant be found";
    }

}
