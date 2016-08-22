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
            "password" => "Passowrd",
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
        <string name='author'>Alejandro Peña Florentín</string>
        <string name='title'>This is the title</string>
        <array name='user'>
            <string name='name'>user name</string>
            <string name='password'>password</string>
            <array name='connection'>
                <string name='user'>user</string>
                <string name='password'>password</string>
                <string name='database'>database</string>
            </array>
        </array>
    </values>
    <values lang='es'>
        <string name='author'>Alejandro Peña Florentín</string>
        <string name='title'>Esto es el título</string>
        <array name='user'>
            <string name='name'>nombre de usuario</string>
            <string name='password'>contraseña</string>
            <array name='connection'>
                <string name='user'>usuario</string>
                <string name='password'>contraseña</string>
                <string name='database'>base de datos</string>
            </array>
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
    public function testLocalizeModuleJsonGetLocales() {
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
     * @depends testLocalizeModuleJsonSetLocale
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
}
