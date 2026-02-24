<?php
// app/core/App.php
// A simple router and request handler for our MVC

class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        $methodName = null;

        // API routing prefix /api
        if (isset($url[0]) && strtolower($url[0]) === 'api') {
            // remove 'api' segment
            array_shift($url);
            if (isset($url[0])) {
                // controller name after api, e.g. api/auth -> ApiAuthController
                $this->controller = 'Api' . ucwords($url[0]) . 'Controller';
                array_shift($url);
            }
            if (isset($url[0])) {
                $methodName = strtolower($url[0]);
                array_shift($url);
            }
        } else {
            // special routing for auth actions to avoid needing /auth prefix
            $authMethods = ['login', 'register', 'logout'];
            if (isset($url[0]) && in_array(strtolower($url[0]), $authMethods, true)) {
                // route to AuthController
                $this->controller = 'AuthController';
                $methodName = strtolower($url[0]);
                unset($url[0]);
            } else {
                // default controller detection based on URL segment
                if (isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]) . 'Controller.php')) {
                    $this->controller = ucwords($url[0]) . 'Controller';
                    unset($url[0]);
                }
                $methodName = isset($url[0]) ? $url[0] : null;
            }
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // method
        if ($methodName && method_exists($this->controller, $methodName)) {
            $this->method = $methodName;
            unset($url[0]);
        }

        // params
        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl()
    {
        // first, try the rewritten query parameter (used by Apache/nginx)
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
        } else {
            // fallback for the built-in PHP server or when rewrite not available
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $url = trim($uri, '/');
        }
        $url = filter_var($url, FILTER_SANITIZE_URL);
        if ($url === '') {
            return [];
        }
        return explode('/', $url);
    }
}
