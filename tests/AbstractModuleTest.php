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
 * Description of AbstractModuleTest
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class AbstractModuleTest extends \PHPUnit_Framework_TestCase
{

    private $name = "TestModule";
    private $module;

    public function setUp() {
        $this->module = new TestModule($this->name);
    }

    public function tearDown() {
        
    }

    /**
     * @test
     */
    public function testAbstractModuleGetName() {
        $this->assertEquals($this->name, $this->module->getModuleName());
    }

}

class TestModule extends \Tight\Modules\AbstractModule
{

    public function onConfigChange() {
        
    }

    public function onLoad() {
        
    }

    public function onRemove() {
        
    }

}
