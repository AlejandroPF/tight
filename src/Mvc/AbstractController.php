<?php

namespace Tight;

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
 * AbstractController class
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
abstract class AbstractController
{

    /**
     * @var \Tight\Mvc\AbstractView
     */
    private $view;

    /**
     * @var \Tight\Mvc\AbstractModel
     */
    private $model;

    public function __construct(AbstractModel $model, AbstractView $view) {
        $this->setView($view);
        $this->setModel($model);
        $this->onLoad();
    }

    /**
     * Gets the view
     * @return \Tight\Mvc\AbstractView
     */
    public function getView() {
        return $this->view;
    }

    /**
     * Gets the model
     * @return \Tight\Mvc\AbstractModel
     */
    public function getModel() {
        return $this->model;
    }

    /**
     * Sets the view
     * @param \Tight\Mvc\AbstractView $view View
     * @return \Tight\Mvc\AbstractController Fluent setter
     */
    public function setView(AbstractView $view) {
        $this->view = $view;
        return $this;
    }

    /**
     * Sets the model
     * @param \Tight\Mvc\AbstractModel $model Model
     * @return \Tight\Mvc\AbstractController Fluent setter
     */
    public function setModel(AbstractModel $model) {
        $this->model = $model;
        return $this;
    }

    /**
     * Event fired when the controller is instanciated 
     */
    abstract public function onLoad();

    /**
     * Event fired before rendering the view
     */
    abstract public function onRender();

    /**
     * Event fired after rendering the view
     */
    abstract public function onFinish();

    /**
     * Renders the view
     */
    public function render() {
        // Fire \Tight\Mvc\AbstractView::onLoad event
        $this->view->onLoad();
        // Fire \Tight\Mvc\AbstractController::onRender event
        $this->onRender();
        $variables = $this->model->getVars();
        foreach ($variables as $key => $value) {
            $this->view->assign($key, $value);
        }
        $this->view->render();
        // Fires \Tight\Mvc\AbstractController::onFinish event
        $this->onFinish();
    }

}
