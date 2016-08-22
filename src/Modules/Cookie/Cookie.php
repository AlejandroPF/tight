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

namespace Tight\Modules\Cookie;

/**
 * Description of Cookie
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class Cookie extends \Tight\Modules\AbstractModule
{

    public $cookieOptions = [];

    public function __construct(CookieConfig $config = null) {
        parent::__construct("CookieModule");
        if (null == $config) {
            $config = new CookieConfig;
        }
        $this->setConfig($config);
    }

    public function set($name, $value, $options = []) {
        return setcookie($name, $value, time() + $this->getConfig()->expire, $this->getConfig()->path, $this->getConfig()->domain, $this->getConfig()->secure, $this->getConfig()->httponly);
    }

    public function remove($name) {
        return setcookie($name, "", -3600, $this->getConfig()->path, $this->getConfig()->domain, $this->getConfig()->secure, $this->getConfig()->httponly);
    }

    public function get($name) {
        if (isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        } else {
            return null;
        }
    }

    public function clear() {
        $cookies = $_COOKIE;
        if (count($cookies) > 0) {
            foreach ($cookies as $key => $value) {
                $this->remove($key);
            }
        }
    }

    public function onConfigChange() {
        
    }

    public function onLoad() {
        
    }

    public function onRemove() {
        
    }

}
