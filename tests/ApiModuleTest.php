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
 * ApiModuleTest class for Tight\Modules\Api package
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class ApiModuleTest extends \PHPUnit_Framework_TestCase
{

    private $response = "Response!";
    private $module;

    public function setUp() {
        $this->module = new \Tight\Modules\Api\Api(true, $this->response);
    }

    public function tearDown() {
        
    }

    /**
     * @test
     */
    public function testGetError() {
        $this->assertTrue($this->module->getError());
    }

    /**
     * @test
     * @depends testGetError
     */
    public function testSetError() {
        $this->module->setError(FALSE);
        $this->assertFalse($this->module->getError());
        $this->module->setError(true);
        $this->assertTrue($this->module->getError());
    }

    /**
     * @test
     */
    public function testGetResponse() {
        $this->assertEquals($this->response, $this->module->getResponse());
    }

    /**
     * @test
     * @depends testGetResponse
     */
    public function testSetResponse() {
        $expected = "This is the response message";
        $this->module->setResponse($expected);
        $this->assertEquals($expected, $this->module->getResponse());
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function testGet() {
        $expected = [
            "error" => true,
            "response" => $this->response
        ];
        $this->assertEquals(json_encode($expected), $this->module->get());
    }

}
