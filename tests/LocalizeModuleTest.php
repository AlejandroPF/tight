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
 * Description of LocalizeModuleTest
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class LocalizeModuleTest extends \PHPUnit_Framework_TestCase
{

    public static $resourceFolder = "resources";
    private $locale_en = [
        "app" => "Test App",
        "data" => [
            "name" => "Name",
            "user" => "User",
            "password" => "Passowrd",
        ]
    ];
    private $locale_es = [
        "app" => "Test de aplicación",
        "data" => [
            "name" => "Nombre",
            "user" => "Usuario",
            "password" => "Contraseña"
        ]
    ];
    private $locale_fr = [
        "app" => "Test d'application",
        "data" => [
            "name" => "Nom",
            "user" => "Utilisateur",
            "password" => "Mot de passe"
        ]
    ];
    private $config = [
    ];
    private $module;

    public function setUp() {
        $valuesFile_en = self::$resourceFolder . "/values.json";
        $valuesFile_es = self::$resourceFolder . "/values_es.json";
        $valuesFile_fr = self::$resourceFolder . "/values_fr.json";
        if (!is_dir(self::$resourceFolder)) {
            mkdir(self::$resourceFolder);
        }
        if (!is_file($valuesFile_en)) {
            file_put_contents($valuesFile_en, json_encode($this->locale_en));
        }
        if (!is_file($valuesFile_es)) {
            file_put_contents($valuesFile_es, json_encode($this->locale_es));
        }
        if (!is_file($valuesFile_fr)) {
            file_put_contents($valuesFile_fr, json_encode($this->locale_fr));
        }
        $this->config = [
            "resourceFolder" => "./" . self::$resourceFolder . "/"
        ];
        $this->module = new \Tight\Modules\Localize\Localize($this->config);
    }

    public function tearDown() {
        
    }

    /**
     * @test
     * @expectedException \Tight\Modules\ModuleException
     */
    public function testLocalizeModuleExceptionDirectoryNotFound() {
        $config = ["resourceFolder" => "directoryNotFound"];
        $this->setExpectedException("\Tight\Modules\ModuleException");
        new \Tight\Modules\Localize\Localize($config);
    }

    /**
     * @test
     * @expectedException \Tight\Modules\ModuleException
     */
    public function testLocalizeModuleExceptionInvalidLocale() {
        $config = [
            "resourceFolder" => self::$resourceFolder,
            "resourceFileName" => "strings"
        ];
        $this->setExpectedException("\Tight\Modules\ModuleException");
        new \Tight\Modules\Localize\Localize($config);
    }

    /**
     * @test
     */
    public function testLocalizeModuleGetConfig() {
        $config = new \Tight\Modules\Localize\LocalizeConfig($this->config);
        $this->assertEquals($config, $this->module->getConfig());
    }

    /**
     * @test
     */
    public function testLocalizeModuleGetAppConfig() {
        // The locale section in Tight\TightConfig is empty due to the method
        // of instantiating the module class (calling the class instead of 
        // Tight\Tight)
        $config = new \Tight\TightConfig(["locale" => []]);
        $this->assertEquals($config, $this->module->getAppConfig());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function testLocalizeModuleInvalidArgumentException() {
        $this->setExpectedException("\InvalidArgumentException");
        new \Tight\Modules\Localize\Localize("This is a string");
    }

    /**
     * @test
     */
    public function testLocalizeModuleGetValues() {
        $expected = $this->locale_en;
        $this->assertEquals($expected, $this->module->getValues());
    }

    /**
     * @test
     */
    public function testLocalizeModuleGetLocales() {
        $expected = ["en", "es", "fr"];
        $this->assertEquals($expected, $this->module->getLocales());
    }

    /**
     * @test
     * @depends testLocalizeModuleGetValues
     * @covers \Tight\Modules\Localize\Localize::setLocale
     */
    public function testLocalizeModuleSetLocale() {
        $expected = $this->locale_es;
        $this->module->setLocale("es");
        $this->assertEquals($expected, $this->module->getValues());
    }

    /**
     * @test
     * @depends testLocalizeModuleSetLocale
     */
    public function testLocalizeModuleGet() {
        $this->assertEquals($this->locale_en['app'], $this->module->get("app"));
        $this->assertEquals($this->locale_en['data']['name'], $this->module->get("data")['name']);
        $this->module->setLocale("es");
        $this->assertEquals($this->locale_es['app'], $this->module->get("app"));
        $this->assertEquals($this->locale_es['data']['name'], $this->module->get("data")['name']);
        $this->module->setLocale("fr");
        $this->assertEquals($this->locale_fr['app'], $this->module->get("app"));
        $this->assertEquals($this->locale_fr['data']['name'], $this->module->get("data")['name']);
        $this->module->setLocale("en");
        $this->assertEmpty($this->module->get("keyNotDefined"));
    }

}
