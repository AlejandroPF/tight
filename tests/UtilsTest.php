<?php

namespace Tight\Tests;

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

use Tight\Utils;

/**
 * Description of UtilsTest
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class UtilsTest extends \PHPUnit_Framework_TestCase
{

    public function setUp() {
        
    }

    public function tearDown() {
        
    }

    /**
     * @test
     */
    public function testUtilsRemoveString() {
        $string = "abcdefgh";
        $this->assertEquals("defgh", \Tight\Utils::removeSubstring($string, "abc"));
        $this->assertEquals("abcgh", \Tight\Utils::removeSubstring($string, "def"));
        $this->assertEquals("abcdef", \Tight\Utils::removeSubstring($string, "gh"));
        $this->assertEquals("abcdefgh", \Tight\Utils::removeSubstring($string, "ff"));
        $this->assertEquals("abcdefgh", \Tight\Utils::removeSubstring($string, "xyz"));
    }

    /**
     * @test
     */
    public function testUtilsInString() {
        $string = "abcde";
        $this->assertTrue(Utils::inString($string, "ab"));
        $this->assertTrue(Utils::inString($string, "cd"));
        $this->assertTrue(Utils::inString($string, "de"));
        $this->assertFalse(Utils::inString($string, "ef"));
    }

    /**
     * @test
     */
    public function testUtilsAddTrailingSlash() {
        $path = "/etc/php5";
        $this->assertEquals("/etc/php5/", Utils::addTrailingSlash($path));
        $this->assertEquals("/etc/php5/", Utils::addTrailingSlash($path . "/"));
    }

    /**
     * @test
     */
    public function testUtilsFilterPath() {
        $pathWin = "C:\\php\\ext";
        $pathUnx = "/var/htdocs";
        $this->assertEquals("C:/php/ext/", Utils::filterPath($pathWin));
        $this->assertEquals("/var/htdocs/", Utils::filterPath($pathUnx));
        $this->assertEquals("/var/htdocs/", Utils::filterPath($pathUnx . "/"));
    }

    /**
     * @test
     */
    public function testUtilsRemoveDouble() {
        $this->assertEquals("String", Utils::removeDouble("Stringg", "g"));
        $this->assertEquals("/etc/php", Utils::removeDouble("/etc//php", "/"));
        $this->assertEquals("Testing", Utils::removeDouble("Testing", "u"));
    }

    /**
     * @test
     */
    public function testUtilsGetSlicedFile() {
        $expected = [
            "name" => "UtilsTest",
            "ext" => "php"
        ];
        $this->assertEquals($expected, \Tight\Utils::getSlicedFile(__FILE__));
    }

}
