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
 * Description of ModuleLoaderTest
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class ModuleLoaderTest extends \PHPUnit_Framework_TestCase
{

    private $loader;
    private $testModule;

    public function setUp() {

        $this->loader = new \Tight\Modules\ModuleLoader(new \Tight\Tight);
        $this->testModule = new TestLoaderModule("TestModule");
    }

    public function tearDown() {
        
    }

    /**
     * @test
     */
    public function testModuleLoaderGetModules() {
        $expected = [];
        $this->assertEquals($expected, $this->loader->getModules());
        $this->assertNull($this->loader->getModule("undefinedModule"));
    }

    /**
     * @test
     * @depends testModuleLoaderGetModules
     */
    public function testModuleLoaderAdd() {
        $key = $this->testModule->getModuleName();
        $expected = [$key => $this->testModule];
        $this->loader->add($this->testModule);
        $this->assertEquals($expected, $this->loader->getModules());
    }

    /**
     * @test
     * @depends testModuleLoaderAdd
     * @expectedException \Tight\Modules\ModuleException
     */
    public function testModuleLoaderAddWithException() {
        $this->loader->add($this->testModule);
        $this->setExpectedException("\Tight\Modules\ModuleException");
        $this->loader->add($this->testModule);
    }

    /**
     * @test
     * @depends testModuleLoaderAdd
     */
    public function testModuleLoaderGetModule() {
        $this->loader->add($this->testModule);
        $modName = $this->testModule->getModuleName();
        $expected = $this->testModule;
        $this->assertEquals($expected, $this->loader->getModule($modName));
    }

    /**
     * @test
     * @depends testModuleLoaderAdd
     */
    public function testModuleLoaderRemove() {
        $this->loader->add($this->testModule);
        $this->assertTrue($this->loader->remove($this->testModule->getModuleName()));
        $this->assertFalse($this->loader->remove($this->testModule->getModuleName()));
    }

}

class TestLoaderModule extends \Tight\Modules\AbstractModule
{

    public function onConfigChange() {
        
    }

    public function onLoad() {
        
    }

    public function onRemove() {
        
    }

}
