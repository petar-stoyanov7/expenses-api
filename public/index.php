<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

//session_start();
//ob_start();
require dirname(__DIR__).'/Core/Helpers.php';

spl_autoload_register(function ($class) {
    $root = dirname(__DIR__);
    $path = explode('\\', $class);
    $file = $root . '/'  . str_replace('\\', '/', $class);
    $subDir =  $file . '/' . end($path) . '.php';
    $file .=  '.php';
    if (is_readable($file)) {
        require $file;
    } elseif (is_readable($subDir)) {
        require $subDir;
    }
});

$router = new Core\Router();

$url = $_SERVER['REQUEST_URI'];
$router->dispatch($url);

//ob_end_flush();