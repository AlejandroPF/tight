<?php

/*
 * The MIT License
 *
 * Copyright 2016 Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
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

namespace Tight\Mvc;

/**
 * AbstractView class
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
abstract class AbstractView
{

    /**
     * @var \Tight\Tight Tight App
     */
    private $app;

    /**
     * @var \Smarty Smarty object for working with templates
     */
    private $smarty;

    /**
     * @var string Template source
     */
    private $template;

    public function __construct($tpl) {
        $this->template = $tpl;
        $this->app = \Tight\Tight::getInstance();
        $this->smarty = $this->app->getSmarty();
    }

    /**
     * Renders the smarty template
     * @uses \Smarty::display() Renders the view
     */
    public function render() {
        $this->smarty->display($this->template);
    }

    /**
     * Delegate method from \Smarty::assign
     * @param string $tpl_var
     * @param string  $value
     * @param boolean $nocache
     * @uses \Smarty::assign 
     */
    public function assign($tpl_var, $value = null, $nocache = false) {
        $this->smarty->assign($tpl_var, $value, $nocache);
    }

    /**
     * Gets the Tight App
     * @return \Tight\Tight
     */
    public function getApp() {
        return $this->app;
    }

    /**
     * Event fired before rendering the template
     */
    abstract public function onLoad();
}
