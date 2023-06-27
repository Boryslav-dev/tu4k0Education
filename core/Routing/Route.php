<?php

declare(strict_types=1);

namespace Framework\Routing;

use Framework\MVC\BaseController;

class Route
{
    /**
     * @var array
     */
    public static array $routes = [];

    /**
     * @param string $route
     * @param array $callback
     * @return void
     */
    public static function get(string $route, array $callback): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            self::setRoute($route, $callback);
        }
    }

    /**
     * @param string $route
     * @param array $callback
     * @return void
     */
    public static function post(string $route, array $callback): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            self::setRoute($route, $callback);
        }
    }

    /**
     * @param string $route
     * @param array $callback
     * @return void
     */
    public static function put(string $route, array $callback): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "PUT") {
            self::setRoute($route, $callback);
        }
    }

    /**
     * @param string $route
     * @param array $callback
     * @return void
     */
    public static function delete(string $route, array $callback): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
            self::setRoute($route, $callback);
        }
    }

    /**
     * @return mixed
     */
    public static function runRoute(): mixed
    {
        $uri = explode('?', $_SERVER['REQUEST_URI']);
        $path = $uri[0];
        foreach (self::$routes as $route => $action) {
            if (preg_match($route, $path, $matches)) {
                $controller = new $action[0]();
                $action[0] = $controller;
                return call_user_func_array($controller::class . "::" . $action[1], array($matches[1]));
            }
        } return call_user_func(BaseController::pageNotFound());
    }

    /**
     * @param string $route
     * @param array $callback
     * @return void
     */
    private static function setRoute(string $route, array $callback): void
    {
        if (str_contains($route, '{')) {
            $startPos = strpos($route, '{');
            $endPos = strpos($route, '}') + 1;
            $route = str_replace(substr($route, $startPos, $endPos - $startPos), '(\w+)', $route);
        }
        $pattern = '#^' . str_replace('/', '\/', $route) . "$#";
        self::$routes[$pattern] = $callback;
    }
}
