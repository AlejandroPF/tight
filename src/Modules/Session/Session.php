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

namespace Tight\Modules\Session;

/**
 * Session class module
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class Session extends \Tight\Modules\AbstractModule
{

    private $sessionId;

    public function __construct() {
        parent::__construct("SessionModule");
        if (session_status() != PHP_SESSION_DISABLED) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $this->sessionId = session_id();
        } else {
            throw new \Tight\Exception\ModuleException("Session is disabled");
        }
    }

    public function get($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return null;
        }
    }

    public function set($key, $value) {
        $_SESSION[$key] = $value;
        return $this;
    }

    public function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        } else {
            return false;
        }
    }

    public function getAll() {
        return $_SESSION;
    }

    public function onConfigChange() {
        
    }

    public function onLoad() {
        
    }

    public function onRemove() {
        
    }

//put your code here
}
