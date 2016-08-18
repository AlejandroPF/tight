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
 * Description of TightTest
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class TightTest extends \PHPUnit_Framework_TestCase
{

    public $app;
    public $config = [
        "basePath" => __DIR__,
        "smarty" => [
            "template_dir" => "./templates",
            "compile_dir" => "./templates_c",
            "config_dir" => "./configs",
            "cache_dir" => "./cache"
        ],
        "mvc" => [
            "enableRouter" => false,
            "indexName" => "Root",
            "controller_dir" => "./controllers/",
            "model_dir" => "./models/",
            "view_dir" => "./views/",
        ],
        "connection" => [
            "user" => "root",
            "password" => "root",
            "database" => "mysql"
        ]
    ];
    private $directories = ["res", "controllers", "models", "views"];
    private $files = ["./res/values.json"];

    public function setUp() {
        $dirSize = count($this->directories);
        for ($index = 0; $index < $dirSize; $index++) {
            if (!is_dir($this->directories[$index])) {
                mkdir($this->directories[$index]);
            }
        }
        $fileSize = count($this->files);
        for ($index = 0; $index < $fileSize; $index++) {
            if (!is_file($this->files[$index])) {
                file_put_contents($this->files[$index], "");
            }
        }
        $config = new \Tight\TightConfig($this->config);
        $this->app = new \Tight\Tight($config);
    }

    public function tearDown() {
        $fileSize = count($this->files);
        $dirSize = count($this->directories);
        for ($index = 0; $index < $fileSize; $index++) {
            if (is_file($this->files[$index])) {
                unlink($this->files[$index]);
            }
        }
        for ($index = 0; $index < $dirSize; $index++) {
            if (is_dir($this->directories[$index])) {
                rmdir($this->directories[$index]);
            }
        }
    }

    /**
     * @test
     */
    public function testTightConfig() {
        $expectedClass = new \Tight\TightConfig($this->config);
        $app = new \Tight\Tight($this->config);
        $this->assertEquals($expectedClass, $app->getConfig());
        unset($app);
        $app = new \Tight\Tight;
        $app->setConfig($this->config);
        $this->assertEquals($expectedClass,$app->getConfig());
    }

    /**
     * @test
     * @covers Tight\Tight::getRouter
     */
    public function testTightGetRouter() {
        $router = $this->app->getRouter();
        $this->assertEquals(new \Tight\Router($this->config["basePath"]), $router);
    }

    /**
     * @test
     */
    public function testTightGetSmarty() {
        $expected = new \Smarty;
        $this->assertEquals($expected->template_dir, $this->app->getSmarty()->template_dir);
        $this->assertEquals($expected->compile_dir, $this->app->getSmarty()->compile_dir);
        $this->assertEquals($expected->cache_dir, $this->app->getSmarty()->cache_dir);
        $this->assertEquals($expected->config_dir, $this->app->getSmarty()->config_dir);
    }

}
