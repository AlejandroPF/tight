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

namespace Tight\Tests;

/**
 * Description of RouterTest
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{

    private $router;

    /**
     * @covers Tight\Router::__construct
     */
    public function setUp() {
        $this->router = new \Tight\Router("/");
        $this->router->get("/hello/", function() {
            return "Hello";
        });
        $this->router->get("/hello/:id", function($id) {
            return "Hello " . $id;
        });
        $this->router->post("/world/", function() {
            return "world";
        });
        $this->router->post("/world/:id", function($id) {
            return $id . " world";
        });
        $this->router->map(["get", "post"], "/map/", function() {
            return "map";
        });
        $this->router->update("/upd/", function() {
            return "upd";
        });
        $this->router->delete("/del/", function() {
            return "del";
        });
        $this->router->get("/middle/", function() {
            return "mid1 ";
        }, function() {
            return "mid2 ";
        }, function() {
            return "end";
        });
        $this->router->notFound(function() {
            return "Error 404: Page not found";
        });
    }

    public function tearDown() {
        
    }

    /**
     * @test
     * @covers \Tight\Router::dispatch
     * @covers \Tight\Router::getRoutes
     */
    public function testRouterDispatch() {
        $firstRoute = $this->router->getRoutes()[0];
        $actual = $this->router->dispatch("/hello/", "get");
        $expected = $firstRoute->dispatch();
        $this->assertEquals($actual, $expected);
    }

    /**
     * @test
     * @covers Tight\Router::notFound
     * @covers \Tight\Router::dispatchNotFound
     * @depends testRouterDispatch
     */
    public function testRouterNotFound() {
        $expected = $this->router->dispatch("/invalid/url/", "post");
        $this->assertEquals($expected, "Error 404: Page not found");
        return $expected;
    }

    /**
     * @test
     * @covers Tight\Router::get
     * @covers Tight\Router::__construct
     * @depends testRouterNotFound
     */
    public function testRouterGet($notFound) {
        $this->assertEquals($this->router->dispatch("/hello/", "get"), "Hello");
        $this->assertEquals($this->router->dispatch("/hello/", "post"), $notFound);
        $this->assertEquals($this->router->dispatch("/hello/world", "get"), "Hello world");
    }

    /**
     * @test
     * @covers Tight\Router::post
     * @depends testRouterNotFound
     */
    public function testRouterPost($notFound) {
        $this->assertEquals($this->router->dispatch("/world/", "post"), "world");
        $this->assertEquals($this->router->dispatch("/world/", "get"), $notFound);
        $this->assertEquals($this->router->dispatch("/world/hello", "post"), "hello world");
    }

    /**
     * @test
     * @covers Tight\Router::map
     * @covers Tight\Router::url
     * @depends testRouterNotFound
     */
    public function testRouterMap($notFound) {
        $this->assertEquals($this->router->dispatch("/map/", "post"), "map");
        $this->assertEquals($this->router->dispatch("/map/", "get"), "map");
        $this->assertEquals($this->router->dispatch("/map/", "options"), $notFound);
    }

    /**
     * @test
     * @covers Tight\Router::update
     * @depends testRouterNotFound
     */
    public function testRouteUpdate($notFound) {
        $this->assertEquals($this->router->dispatch("/upd/", "update"), "upd");
        $this->assertEquals($this->router->dispatch("/upd/", "get"), $notFound);
    }

    /**
     * @test
     * @covers Tight\Router::delete
     * @depends testRouterNotFound
     */
    public function testRouteDelete($notFound) {
        $this->assertEquals($this->router->dispatch("/del/", "delete"), "del");
        $this->assertEquals($this->router->dispatch("/del/", "post"), $notFound);
    }

    /**
     * @test
     * @covers Tight\Route::setMiddleware
     * @depends testRouterNotFound
     */
    public function testRouteMiddleware($notFound) {
        $this->assertEquals($this->router->dispatch("/middle/", "get"), "mid1 mid2 end");
        $this->assertEquals($this->router->dispatch("/middle/", "post"), $notFound);
    }

    /**
     * @test
     */
    public function testRouterGetRequestUrn() {
        $this->assertEquals($_SERVER['REQUEST_URI'], $this->router->getRequestUrn());
    }

    /**
     * @test
     * @covers \Tight\Router::runMvc
     * @covers \Tight\Router::registerClasses
     */
    public function testRouterMvc() {
        $this->router->runMvc();
        $this->expectOutputString("");
    }

}
