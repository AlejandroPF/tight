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

namespace Tight\Modules\Api;

/**
 * Api class
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class Api extends \Tight\Modules\AbstractModule
{

    private $error;
    private $response;

    public function __construct($error = true, $response = "") {
        parent::__construct("ApiModule", "v1.0");
        $this->setError($error);
        $this->setResponse($response);
    }

    public function onConfigChange() {
        
    }

    public function onLoad() {
        
    }

    public function onRemove() {
        
    }

    public function getError() {
        return $this->error;
    }

    public function getResponse() {
        return $this->response;
    }

    public function setError($error) {
        $this->error = $error;
        return $this;
    }

    public function setResponse($response) {
        $this->response = $response;
        return $this;
    }

    public function get() {
        header("Content-type: application/json");
        $output = [
            "error" => $this->getError(),
            "response" => $this->getResponse()
        ];
        return json_encode($output);
    }

}
