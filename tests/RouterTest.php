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

/**
 * Description of RouterTest
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class RouterTest extends PHPUnit_Framework_TestCase
{

    private $router;

    /**
     * @covers Tight\Router::__construct
     */
    public function setUp() {
        $this->router = new Tight\Router("");
        $this->router->get("/hello/", function() {
            echo "Hello";
        });
        $this->router->get("/hello/:id", function($id) {
            echo "Hello " . $id;
        });
        $this->router->post("/world/", function() {
            echo "world";
        });
        $this->router->post("/world/:id", function($id) {
            echo $id . " world";
        });
        $this->router->map(["get", "post"], "/map/", function() {
            echo "map";
        });
        $this->router->update("/upd/", function() {
            echo "upd";
        });
        $this->router->delete("/del/", function() {
            echo "del";
        });
        $this->router->get("/middle/", function() {
            echo "mid1 ";
        }, function() {
            echo "mid2 ";
        }, function() {
            echo "end";
        });
    }

    public function tearDown() {
        
    }

    /**
     * @test
     * @covers Tight\Router::get
     * @covers Tight\Router::__construct
     */
    public function testRouterGet() {
        $output = "";
        $this->router->dispatch("/hello/", "get");
        $this->expectOutputString("Hello");
        $output .= "Hello";
        $this->router->dispatch("/hello/", "post");
        $this->expectOutputString($output . "Page not found");
        $output .= "Page not found";
        $this->router->dispatch("/hello/world", "get");
        $this->expectOutputString($output . "Hello world");
        $output.="Hello world";
    }

    /**
     * @test
     * @covers Tight\Router::post
     * @covers Tight\Router::dispatch
     */
    public function testRouterPost() {
        $output = "";
        $this->router->dispatch("/world/", "post");
        $this->expectOutputString($output . "world");
        $output .= "world";
        $this->router->dispatch("/world/", "get");
        $this->expectOutputString($output . "Page not found");
        $output .= "Page not found";
        $this->router->dispatch("/world/hello", "post");
        $this->expectOutputString($output . "hello world");
        $output .= "Hello world";
    }

    /**
     * @test
     * @covers Tight\Router::map
     * @covers Tight\Router::url
     * @covers Tight\Router::dispatch
     */
    public function testRouterMap() {
        $output = "";
        $this->router->dispatch("/map/", "post");
        $this->expectOutputString($output . "map");
        $output .= "map";
        $this->router->dispatch("/map/", "get");
        $this->expectOutputString($output . "map");
        $output .= "map";
        $this->router->dispatch("/map/", "options");
        $this->expectOutputString($output . "Page not found");
        $output .= "Page not found";
    }

    /**
     * @test
     * @covers Tight\Router::update
     * @covers Tight\Router::dispatch
     */
    public function testRouteUpdate() {
        $output = "";
        $this->router->dispatch("/upd/", "update");
        $this->expectOutputString($output . "upd");
        $output .= "upd";
        $this->router->dispatch("/upd/", "get");
        $this->expectOutputString($output . "Page not found");
        $output .= "Page not found";
    }

    /**
     * @test
     * @covers Tight\Router::delete
     * @covers Tight\Router::dispatch
     */
    public function testRouteDelete() {
        $output = "";
        $this->router->dispatch("/del/", "delete");
        $this->expectOutputString($output . "del");
        $output .= "del";
        $this->router->dispatch("/del/", "post");
        $this->expectOutputString($output . "Page not found");
        $output .= "Page not found";
    }

    /**
     * @test
     * @covers Tight\Route::setMiddleware
     * @covers Tight\Router::dispatch
     */
    public function testRouteMiddleware() {
        $output = "";
        $this->router->dispatch("/middle/", "get");
        $this->expectOutputString($output . "mid1 mid2 end");
        $output .= "mid1 mid2 end";
        $this->router->dispatch("/middle/", "post");
        $this->expectOutputString($output . "Page not found");
        $output .= "Page not found";
    }

    /**
     * @test
     * @covers Tight\Router::notFound
     */
    public function testRouteNotFound() {
        $this->router->notFound(function() {
            echo "Error 404";
        });
        $this->router->dispatch("/invalid/url", "post");
        $this->expectOutputString("Error 404");
    }

}
