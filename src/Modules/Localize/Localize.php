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
 * Localize module for translations
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class Localize extends \Tight\Modules\AbstractModule
{

    /**
     * @var string Resource file type
     */
    private $resourceFileType;

    /**
     * @var string Current locale
     */
    private $locale;

    /**
     * @var array Associative array for the current locale
     */
    private $values;

    /**
     * Constructor
     * 
     * @param array|\Tight\Modules\Localize\LocalizeConfig $config
     * @throws \InvalidArgumentException If $config is not an array or an object
     * of \Tight\Modules\Localize\LocalizeConfig class
     */
    public function __construct($config = []) {
        if (is_array($config)) {
            $config = new \Tight\Modules\Localize\LocalizeConfig($config);
        } else if (!$config instanceof \Tight\Modules\Localize\LocalizeConfig) {
            throw new \InvalidArgumentException("Argument 1 passed to " . get_class($this) . " must be an array or an instance of Tight\Modules\Localize\LocalizeConfig");
        }
        parent::__construct("LocalizeModule", "v1.2");
        $this->setConfig($config);
        $this->checkDependences();
        $this->setResourceFileType($this->getConfig()->resourceFileType);
        $this->setLocale($this->getConfig()->defaultLocale);
    }

    /**
     * Sets the resource file type
     * @param string $resourceFileType Resource file type
     * @return \Tight\Modules\Localize\Localize Fluent setter
     */
    public function setResourceFileType($resourceFileType) {
        $this->resourceFileType = $resourceFileType;
        $this->reloadConfig();
        return $this;
    }

    /**
     * Gets the current resource file type
     * @return string Resource file type
     * @see \Tight\Modules\Localize\LocalizeConfig::FILETYPE_INI
     * @see \Tight\Modules\Localize\LocalizeConfig::FILETYPE_JSON
     * @see \Tight\Modules\Localize\LocalizeConfig::FILETYPE_XML
     */
    public function getResourceFileType() {
        return $this->resourceFileType;
    }

    /**
     * Gets all the defined values for the current locale
     * @return array Associative array of values
     */
    public function getValues() {
        return $this->values;
    }

    /**
     * Reloads the class with the new locale
     */
    public function reloadConfig() {
        if (null == $this->locale) {
            $this->locale = $this->getConfig()->defaultLocale;
        }
        $values = null;
        switch ($this->resourceFileType) {
            case LocalizeConfig::FILETYPE_JSON:
                $values = $this->getValuesFromJson();
                break;
            case LocalizeConfig::FILETYPE_XML:
                $values = $this->getValuesFromXml();
                break;
        }
        $this->values = $values;
    }

    /**
     * Checks the dependences for the class
     * @throws \Tight\Exception\ModuleException If resource directory cant be 
     * found
     */
    private function checkDependences() {
        if (!is_dir($this->getConfig()->resourceFolder)) {
            throw new \Tight\Exception\ModuleException("Resource directory not found");
        }
    }

    /**
     * Sets a new locale
     * @param string $locale New defined locale
     * @throws \Tight\Exception\ModuleException If resource default resource file
     * cant be found
     */
    public function setLocale($locale) {
        $this->locale = $locale;
        $this->reloadConfig();
    }

    /**
     * Gets the available locales
     * @return array Available locales
     */
    public function getLocales() {
        $output = [];
        switch ($this->resourceFileType) {
            case LocalizeConfig::FILETYPE_JSON:
                $output = $this->getLocalesFromJson();
                break;
            case LocalizeConfig::FILETYPE_XML:
                $output = $this->getLocalesFromXml();
                break;
            case LocalizeConfig::FILETYPE_INI:
                break;
        }
        return $output;
    }

    private function getLocalesFromJson() {
        $output = [];
        $directory = $this->getConfig()->resourceFolder;
        $fileName = $this->getConfig()->resourceFileName;
        $dir = opendir($directory);
        $files = [];
        while ($entry = readdir($dir)) {
            if (strpos($entry, $fileName) !== false) {
                $files[] = $entry;
            }
        }
        foreach ($files as $element) {
            $file = \Tight\Utils::getSlicedFile($directory . $element);
            //Removes extension
            $name = $file["name"];
            $explode = explode($this->getConfig()->langSeparator, $name);
            // Get the locale of the defined file type
            if ($file["ext"] == $this->getConfig()->resourceFileType) {
                if (count($explode) > 1) {
                    $output[] = $explode[count($explode) - 1];
                } else {
                    $output[] = $this->getConfig()->defaultLocale;
                }
            }
        }
        return $output;
    }

    /**
     * Gets the json values from the json file
     * @return array String values
     * @throws \Tight\Exception\ModuleException If json file cant be found
     */
    private function getValuesFromJson() {
        $output = [];
        $folder = \Tight\Utils::addTrailingSlash($this->getConfig()->resourceFolder);
        $fileName = $this->getConfig()->resourceFileName . $this->getConfig()->langSeparator . $this->locale . "." . $this->getConfig()->resourceFileType;
        $file = $folder . $fileName;
        if (is_file($file)) {
            $output = json_decode(file_get_contents($file), JSON_FORCE_OBJECT);
        } else {
            $fileName = $this->getConfig()->resourceFileName . "." . $this->getConfig()->resourceFileType;
            $file = $folder . $fileName;
            if (is_file($file)) {
                $output = json_decode(file_get_contents($file), JSON_FORCE_OBJECT);
            } else {
                throw new \Tight\Exception\ModuleException("Resource file <strong>" . $file . "</strong> not found");
            }
        }
        return $output;
    }

    /**
     * Gets the locales from the xml resource file
     * @return array Array of locales
     */
    private function getLocalesFromXml() {
        $output = [];
        $resources = $this->getXmlResources();
        $size = count($resources->values);
        for ($index = 0; $index < $size; $index++) {
            $output[] = $resources->values[$index]['lang']->__toString();
        }
        return $output;
    }

    /**
     * Gets the xml resource read from the xml resource file
     * @return \SimpleXMLElement XML values for the current locale
     * @throws \Tight\Exception\ModuleException If xml values resource file cant be found
     */
    private function getXmlResources() {
        $output = null;
        if ($this->resourceFileType === LocalizeConfig::FILETYPE_XML) {
            $file = \Tight\Utils::addTrailingSlash($this->getConfig()->resourceFolder) . $this->getConfig()->resourceFileName . "." . $this->resourceFileType;
            if (is_file($file)) {
                $xmlContent = file_get_contents($file);
                $output = new \SimpleXMLElement($xmlContent);
            } else {
                throw new \Tight\Exception\ModuleException("Resource file <strong>" . $file . "</strong> does not exists");
            }
        }
        return $output;
    }

    /**
     * Gets the values from xml resource file
     * @return array Array of values
     * @throws \Tight\Exception\ModuleException If the current locale cant be 
     * found in the xml resource file
     */
    private function getValuesFromXml() {
        $output = [];
        $resources = $this->getXmlResources();
        $locale = null;
        $size = count($resources->values);
        $index = 0;
        while ($index < $size && $locale == null) {
            if ($resources->values[$index]['lang'] == $this->locale) {
                $locale = $resources->values[$index];
            }
            $index++;
        }
        if (null == $locale) {
            $fileName = $this->getConfig()->resourceFileName . "." . $this->getConfig()->resourceFileType;
            throw new \Tight\Exception\ModuleException("Locale <strong>" . $this->locale . "</strong> not found at resource file <strong>" . $fileName . "</strong>");
        }
        return $this->parseXmlStringValues($locale);
    }

    /**
     * Parses a \SimpleXmlElement object to get its values
     * @param \SimpleXMLElement $xml XML object
     * @return array Values
     */
    private function parseXmlStringValues(\SimpleXMLElement $xml) {
        $output = [];
        if ($xml->count() > 0) {
            $children = $xml->children();
            for ($index = 0; $index < count($children); $index++) {
                // If xml tag is <array>, parse without merge
                if ($children[$index]->getName() === "array") {
                    $key = $children[$index]['name'];
                    if (null !== $key) {
                        $output[$key->__toString()] = $this->parseXmlStringValues($children[$index]);
                    }
                } else {
                    $output = array_merge($output, $this->parseXmlStringValues($children[$index]));
                }
            }
        } else {
            $key = $xml['name'];
            if (null !== $key) {
                $output[$key->__toString()] = $xml->__toString();
            }
        }
        return $output;
    }

    /**
     * Gets a value from a defined key
     * @param string $key Key
     * @return string Value defined for the key $key or an empty string if the
     * key is not defined
     */
    public function get($key) {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        } else {
            return "";
        }
    }

    public function onConfigChange() {
        $this->reloadConfig();
    }

    public function onLoad() {
        
    }

    public function onRemove() {
        
    }

}
