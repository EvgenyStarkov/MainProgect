<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/HomeController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/ContentController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/ContentsController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/CollectionController.php';

class Router
{
    private $currentController = null;

    private $routes = ['/views/home/','/views/content/' ,'/views/contents/', '/views/collection/'];

    public function redirect(string $path, array $params)
    {
        $error404 = true;

        if($path === '/' || $path === ''){
            $path = '/views/home/';
        }

        foreach ($this->routes as $route) {

            if ($route === $path) {
                $currentController = $this->getController($path);
                $currentController->index($path, $params);
                $error404 = false;
            }

        }

        if ($error404) {
            $this->error404();
        }

    }

    private function getController($path)
    {
        switch ($path) {
            case '/views/home/';
                $controller = new HomeController();
                break;
            case '/views/content/';
                $controller = new ContentController;
                break;
            case '/views/contents/';
                $controller = new ContentsController;
                break;
            case '/views/collection/';
                $controller = new CollectionController;
                break;
        }

        return $controller;
    }

    private function error404()
    {
        http_response_code(404);
        include $_SERVER['DOCUMENT_ROOT'] . '/views/404/index.php';
    }

}