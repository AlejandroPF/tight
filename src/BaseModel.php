<?php

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

namespace Tight;

/**
 * Description of BaseModel
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class BaseModel
{

    private $data = [];
    private $doc = [];
    private $header = [];
    private $footer = [];

    public function __construct() {
        
    }

    public function addDoc($name, $value) {
        $this->doc[$name] = $value;
        return $this;
    }

    public function addHeader($name, $value) {
        $this->header[$name] = $value;
        return $this;
    }

    public function addFooter($name, $value) {
        $this->footer[$name] = $value;
    }

    public function getDoc() {
        return $this->doc;
    }

    public function getHeaders() {
        return $this->header;
    }

    public function getFooter() {
        return $this->footer;
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    public function __get($name) {
        return (isset($this->data[$name]) ? $this->data[$name] : null);
    }

}