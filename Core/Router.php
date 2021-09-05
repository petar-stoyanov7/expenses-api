<?php

namespace Core;

class Router
{    
    public function dispatch($url)
    {
        $route = $this->extractRoute($url);
        $controller = 'Application\Controllers\\' . $this->convertToStudlyCaps($route['controller']);
        $action = $this->convertToCamelCase($route['action']);
        $params = $route['params'];
        $params = count($params) > 0 ? $this->processParams($route['params']) : null;
        if (class_exists($controller)) {
            $Controller = new $controller();
            $callableAction = $action.'Action';
            if (is_callable([$Controller, $callableAction])) {
                $Controller->$callableAction($params);
            } else {
                echo json_encode("Invalid method [ {$action} ] specified");
            }
        } else {
            echo json_encode("Invalid controller specified: [ {$controller} ]");
        }
    }

    public function extractRoute($url)
    {
        $urlArray = explode('/', $url);

        $result = [];
        $result['controller'] = (array_key_exists(1, $urlArray) && $urlArray[1] !== '') ? $urlArray[1] : 'Index';
        $result['action'] = (array_key_exists(2, $urlArray) && $urlArray[2] !== '') ? $urlArray[2] : 'index';
        $result['params'] = array_slice($urlArray, 3);

        return $result;
    }

    public function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    public function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    public function processParams($params)
    {        
        if (!is_array($params) || (sizeof($params) % 2 !== 0)) {
            return null;
        }

        $result = [];
        for ($i = 0; $i < count($params); $i++) {
            if ($i % 2 === 0) {
                $result[$this->convertToCamelCase($params[$i])] = $params[$i+1];
            }
        }
        return $result;
    }
}