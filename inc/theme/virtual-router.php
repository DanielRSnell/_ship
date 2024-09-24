<?php

class vRouter
{
    private static $routes = [];

    public static function get($uri, $callback)
    {
        self::addRoute('GET', $uri, $callback);
    }

    public static function post($uri, $callback)
    {
        self::addRoute('POST', $uri, $callback);
    }

    public static function put($uri, $callback)
    {
        self::addRoute('PUT', $uri, $callback);
    }

    public static function delete($uri, $callback)
    {
        self::addRoute('DELETE', $uri, $callback);
    }

    public static function any($uri, $callback)
    {
        self::addRoute('ANY', $uri, $callback);
    }

    public static function redirect($from, $to, $status = 302)
    {
        self::addRoute('ANY', $from, function () use ($to, $status) {
            wp_redirect($to, $status);
            exit;
        });
    }

    private static function addRoute($method, $uri, $callback)
    {
        self::$routes[] = [
            'method' => $method,
            'uri' => $uri,
            'callback' => $callback,
        ];
    }

    public static function handle()
    {
        $request_uri = trim($_SERVER['REQUEST_URI'], '/');
        $request_method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes as $route) {
            $pattern = '@^' . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $route['uri']) . '$@D';
            if (preg_match($pattern, $request_uri, $matches) && ($route['method'] == $request_method || $route['method'] == 'ANY')) {
                array_shift($matches);
                $callback = $route['callback'];
                if (is_callable($callback)) {
                    echo call_user_func_array($callback, $matches);
                } elseif (is_string($callback) && strpos($callback, '@') !== false) {
                    list($controller, $method) = explode('@', $callback);
                    $controller = new $controller();
                    echo call_user_func_array([$controller, $method], $matches);
                }
                exit;
            }
        }
    }
}

// Hook into WordPress
add_action('init', ['vRouter', 'handle']);
