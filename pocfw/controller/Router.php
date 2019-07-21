<?php
namespace POCFW\Controller;

use POCFW\Exception\ClassNotFoundException;
use POCFW\Exception\MethodNotFoundException;

class Router implements IRouter {

    protected function getControllerNamespace() {
        return CONFIG['projectNamespace'] . '\\Controller\\';
    }

    public function invokeController() {
        $baseHost = array_values(array_filter(explode('/', CONFIG['baseHost']), function($el) { return $el !== ''; }));
        $requested = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI']), function($el) { return $el !== ''; }));
        for ($i = 0; $i < count($baseHost) && $i < count($requested); $i++) {
            if ($baseHost[$i] == $requested[$i]) {
                array_shift($requested);
            }
        }
        $clazz = $this->getControllerNamespace();
        try {
            if (count($requested) === 0) {
                $clazz .= 'Index';
                $controller = new $clazz;
                $controller->index();
            } else {
                $clazz .= implode('', array_map('ucfirst', explode('_', array_shift($requested))));
                $controller = new $clazz;
                if (count($requested) === 0) {
                    $data = $controller->index();
                } else {
                    $method = array_shift($requested);
                    $reflection = new \ReflectionClass($clazz);
                    if ($reflection->hasMethod($method)) {
                        $data = call_user_func_array(array($controller, $method), $requested);
                    } else {
                        throw new MethodNotFoundException();
                    }
                }
            }
        } catch (ClassNotFoundException|MethodNotFoundException $ex) {
            $clazz = $this->getNotFoundClass();
            $data = $this->invokeNotFoundController();
        }
        if ($data) {
            $view = 'view/html/';
            if (isset($data['view'])) {
                $view .= $data['view'];
            } else {
                if (!isset($reflection)) {
                    $reflection = new \ReflectionClass($clazz);
                }
                $view .= $reflection->getShortName() . '.html';
            }
            $this->loadView($view, $data);
        }
    }

    protected function getNotFoundClass() {
        return $this->getControllerNamespace() . 'NotFound';
    }

    public function invokeNotFoundController() {
        $clazz = $this->getNotFoundClass();
        $controller = new $clazz;
        return $controller->index();
    }

    public function loadView($viewPath, $data) {
        include($viewPath);
    }

}