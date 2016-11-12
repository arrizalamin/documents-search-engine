<?php

namespace App;

use Closure;

class Router
{
    private $routes = [];

    public function add(string $path, Closure $callback)
    {
        $this->routes[$path] = $callback->bindTo($this);
    }
    
    public function render(string $file, array $data = [])
    {
        extract($data);
        require_once 'views/' . $file . '.php';
    }

    public function start()
    {
        $filename = $_SERVER['SCRIPT_NAME'];
        $path = parse_url('http://a.b' . $_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $len = strlen($filename);
        if (strncmp($filename, $path, $len) === 0) {
            $path = substr($path, $len);
            $path = $path === '' ? '/' : $path;
        }

        if (isset($this->routes[$path])) {
            $this->routes[$path]($_REQUEST);
        }
    }
}