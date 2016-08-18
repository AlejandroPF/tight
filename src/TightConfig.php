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
class TightConfig extends BaseConfig
{

    /**
     * @var string Application base path
     */
    public $basePath = null;

    /**
     * @var array Smarty settings
     */
    public $smarty = [
        "template_dir" => "./templates/",
        "compile_dir" => "./templates_c/",
        "config_dir" => "./configs/",
        "cache_dir" => "./cache/"
    ];
    /**
     * @var array MVC settings
     */
    public $mvc = [
        "enableRouter" => false,
        "indexName" => "Root",
        "controller_dir" => "./controllers/",
        "model_dir" => "./models/",
        "view_dir" => "./views/",
    ];

    /**
     * Constructor
     * 
     * Settings can be passed as an array
     * @param array $config Settings
     */
    public function __construct(array $config = []) {
        if (isset($config['basePath'])) {
            $this->basePath = $config['basePath'];
            unset($config['basePath']);
        }
        if (isset($config["smarty"])) {
            $this->smarty = array_replace_recursive($this->smarty, $config["smarty"]);
            unset($config["smarty"]);
        }
        if (isset($config["mvc"])) {
            $this->mvc = array_replace_recursive($this->mvc, $config["mvc"]);
            unset($config["mvc"]);
        }
        // Creates custom config
        if (count($config) > 0) {
            foreach ($config as $key => $value) {
                $this->$key = $value;
            }
        }
    }

}
