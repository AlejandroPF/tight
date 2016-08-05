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

namespace Tight\Modules\Localize;

/**
 * Description of Localize
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class Localize extends \Tight\Modules\AbstractModule
{

    private $config;
    private $locale;
    private $values;

    public function __construct(LocalizeConfig $config) {
        parent::__construct();
        $this->setConfig($config);
        $this->checkDependences();
        $this->setLocale($this->config->defaultLocale);
    }
    public function getValues(){
        return $this->values;
    }
    public function reloadConfig() {
        $this->locale = $this->config->defaultLocale;
    }

    private function checkDependences() {
        if (!is_dir($this->config->resourceFolder)) {
            throw new \Tight\Modules\ModuleException("Resource directory not found");
        }
    }

    public function setConfig(LocalizeConfig $config) {
        $this->config = $config;
        $this->reloadConfig();
    }

    public function setLocale($locale) {
        $this->locale = $locale;
        $folder = \Tight\Utils::addTrailingSlash($this->config->resourceFolder);
        $fileName = $this->config->resourceFileName . $this->config->langSeparator . $locale . "." . $this->config->resourceFileType;
        $file = $folder . $fileName;
        if (is_file($file)) {
            echo $file;
        } else {
            $fileName = $this->config->resourceFileName . "." . $this->config->resourceFileType;
            $file = $folder . $fileName;
            if (!is_file($file)) {
                throw new \Tight\Modules\ModuleException("Resource file <strong>" . $file . "</strong> not found");
            }
        }
        $this->values = json_decode(file_get_contents($file));
    }

    /**
     * Gets the module configuration
     * @return \Tight\Modules\Localize\LocalizeConfig
     */
    public function getConfig() {
        return $this->config;
    }

    public function getLocales() {
        $output = [];
        $directory = $this->config->resourceFolder;
        $fileName = $this->config->resourceFileName;
        $fileType = $this->config->resourceFileType;
        $dir = opendir($directory);
        $files = [];
        while ($entry = readdir($dir)) {
            if (strpos($entry, $fileName) !== false) {
                $files[] = $entry;
            }
        }
        foreach ($files as $element) {
            //Removes extension
            $name = explode(".", $element)[0];
            $explode = explode($this->config->langSeparator, $name);
            if (count($explode) > 1) {
                $output[] = $explode[count($explode) - 1];
            } else {
                $output[] = $this->config->defaultLocale;
            }
        }
        return $output;
    }

    public function get($key) {
        
    }

}
