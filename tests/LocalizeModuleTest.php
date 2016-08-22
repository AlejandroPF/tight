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
    private $localeEn = [
        "app" => "Test App",
        "data" => [
            "name" => "Name",
            "user" => "User",
            "password" => "Password",
        ]
    ];
    private $localeEs = [
        "app" => "Test de aplicación",
        "data" => [
            "name" => "Nombre",
            "user" => "Usuario",
            "password" => "Contraseña"
        ]
    ];
    private $localeFr = [
        "app" => "Test d'application",
        "data" => [
            "name" => "Nom",
            "user" => "Utilisateur",
            "password" => "Mot de passe"
        ]
    ];
    private $config = [
    ];
    private $valuesXml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<resources>
    <values lang='en'>
        <string name='app'>Test App</string>
        <array name='data'>
            <string name='name'>Name</string>
            <string name='user'>User</string>
            <string name='password'>Password</string>
        </array>
    </values>
    <values lang='es'>
        <string name='app'>Test de aplicación</string>
        <array name='data'>
            <string name='name'>Nombre</string>
            <string name='user'>Usuario</string>
            <string name='password'>Contraseña</string>
        </array>
    </values>
    <values lang='fr'>
        <string name='app'>Test d'application</string>
        <array name='data'>
            <string name='name'>Nom</string>
            <string name='user'>Utilisateur</string>
            <string name='password'>Mot de passe</string>
        </array>
    </values>
</resources>";
    private $module;

    public function setUp() {
        $valuesFile_en = self::$resourceFolder . "/values.json";
        $valuesFile_es = self::$resourceFolder . "/values_es.json";
        $valuesFile_fr = self::$resourceFolder . "/values_fr.json";
        $valuesXml = self::$resourceFolder . "/values.xml";
        if (!is_dir(self::$resourceFolder)) {
            mkdir(self::$resourceFolder);
        }
        if (!is_file($valuesFile_en)) {
            file_put_contents($valuesFile_en, json_encode($this->localeEn));
        }
        if (!is_file($valuesFile_es)) {
            file_put_contents($valuesFile_es, json_encode($this->localeEs));
        }
        if (!is_file($valuesFile_fr)) {
            file_put_contents($valuesFile_fr, json_encode($this->localeFr));
        }
        if (!is_file($valuesXml)) {
            file_put_contents($valuesXml, $this->valuesXml);
        }
        $this->config = [
            "resourceFolder" => "./" . self::$resourceFolder . "/"
        ];
        $this->module = new \Tight\Modules\Localize\Localize($this->config);
    }

    public function tearDown() {
        $valuesFile_en = self::$resourceFolder . "/values.json";
        $valuesFile_es = self::$resourceFolder . "/values_es.json";
        $valuesFile_fr = self::$resourceFolder . "/values_fr.json";
        $valuesXml = self::$resourceFolder . "/values.xml";
        $files = [$valuesFile_en, $valuesFile_es, $valuesFile_fr, $valuesXml];
        $size = count($files);
        for ($index = 0; $index < $size; $index++) {
            if (is_file($files[$index])) {
                unlink($files[$index]);
            }
        }
    }

    /**
     * @test
     * @expectedException \Tight\Exception\ModuleException
     */
    public function testModuleExceptionDirectoryNotFound() {
        $config = ["resourceFolder" => "directoryNotFound"];
        $this->setExpectedException("\Tight\Exception\ModuleException");
        new \Tight\Modules\Localize\Localize($config);
    }

    /**
     * @test
     * @expectedException \Tight\Exception\ModuleException
     */
    public function testModuleExceptionInvalidLocale() {
        $config = [
            "resourceFolder" => self::$resourceFolder,
            "resourceFileName" => "strings"
        ];
        $this->setExpectedException("\Tight\Exception\ModuleException");
        new \Tight\Modules\Localize\Localize($config);
    }

    /**
     * @test
     */
    public function testGetConfig() {
        $config = new \Tight\Modules\Localize\LocalizeConfig($this->config);
        $this->assertEquals($config, $this->module->getConfig());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgumentException() {
        $this->setExpectedException("\InvalidArgumentException");
        new \Tight\Modules\Localize\Localize("This is a string");
    }

    /**
     * @test
     */
    public function testJsonGetValues() {
        $expected = $this->localeEn;
        $this->assertEquals($expected, $this->module->getValues());
    }

    /**
     * @test
     */
    public function testJsonGetLocales() {
        $expected = ["en", "es", "fr"];
        $this->assertEquals($expected, $this->module->getLocales());
    }

    /**
     * @test
     * @depends testJsonGetValues
     * @covers \Tight\Modules\Localize\Localize::setLocale
     */
    public function testJsonSetLocale() {
        $expected = $this->localeEs;
        $this->module->setLocale("es");
        $this->assertEquals($expected, $this->module->getValues());
    }

    /**
     * @test
     * @depends testJsonSetLocale
     */
    public function testJsonGet() {
        $this->assertEquals($this->localeEn['app'], $this->module->get("app"));
        $this->assertEquals($this->localeEn['data']['name'], $this->module->get("data")['name']);
        $this->module->setLocale("es");
        $this->assertEquals($this->localeEs['app'], $this->module->get("app"));
        $this->assertEquals($this->localeEs['data']['name'], $this->module->get("data")['name']);
        $this->module->setLocale("fr");
        $this->assertEquals($this->localeFr['app'], $this->module->get("app"));
        $this->assertEquals($this->localeFr['data']['name'], $this->module->get("data")['name']);
        $this->module->setLocale("en");
        $this->assertEmpty($this->module->get("keyNotDefined"));
    }

    /**
     * @test
     */
    public function testSetResourceFileType() {
        $this->module->setResourceFileType(\Tight\Modules\Localize\LocalizeConfig::FILETYPE_XML);
        $this->assertEquals(\Tight\Modules\Localize\LocalizeConfig::FILETYPE_XML, $this->module->getResourceFileType());
    }

    /**
     * @test
     * @depends testSetResourceFileType
     */
    public function testXmlGetValues() {
        $this->module->setResourceFileType(\Tight\Modules\Localize\LocalizeConfig::FILETYPE_XML);
        $this->assertEquals($this->localeEn, $this->module->getValues());
    }

}
