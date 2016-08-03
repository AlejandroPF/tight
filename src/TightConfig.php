<?php

namespace Tight;

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
 * TightConfig class
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class TightConfig
{

    private $config = [
        "basePath" => null,
        "smarty" => [
            "template_dir" => "./templates",
            "compile_dir" => "./templates_c",
            "config_dir" => "./configs",
            "cache_dir" => "./cache"
        ]
    ];

    public function __construct(array $config = []) {
        $this->config = array_replace_recursive($this->config, $config);
    }

    public function __get($name) {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        } else {
            return null;
        }
    }

    public function __set($name, $value) {
        $this->config = array_merge($this->config, [$name => $value]);
    }

}
