<?php

namespace Reign\Router;

class Router
{
    protected $routes = [];

    public function add($method, $pattern, $callback)
    {
        $this->routes[$pattern] = [
            'method' => $method,
            'callback' => $callback,
        ];
    }

    public function get($pattern, $callback)
    {
        $this->add('GET', $pattern, $callback);
    }

    public function post($pattern, $callback)
    {
        $this->add('POST', $pattern, $callback);
    }

    public function run()
    {
        $found = false;

        foreach ($this->routes as $pattern => $route) {
            if ($route['method'] === $_SERVER['REQUEST_METHOD']) {
                $pattern = "#^$pattern$#";
                if (preg_match($pattern, $_SERVER['REQUEST_URI'], $matches)) {
                    $found = true;
                    array_splice($matches, 0, 1);
                    call_user_func($route['callback'], ...$matches);
                    break;
                }
            }
        }

        if (!$found) {
            echo '404';
        }
    }
}
