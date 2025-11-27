<?php

namespace App\Core;

use App\Core\Exceptions\RouteNotFoundException;

class Router
{
    /** @var array $routes */
    protected array $routes = [];

    /**
     * Thêm một route vào bảng điều hướng.
     *
     * @param string $method (GET, POST, PUT, DELETE)
     * @param string $uri
     * @param string $controller (Ví dụ: 'AuthController@index')
     */
    public function add(string $method, string $uri, string $controller): void
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
        ];
    }

    // Các hàm helper (tiện ích)
    public function get(string $uri, string $controller): void
    {
        $this->add('GET', $uri, $controller);
    }

    public function post(string $uri, string $controller): void
    {
        $this->add('POST', $uri, $controller);
    }

    // (Em có thể thêm put, delete, patch sau nếu cần)

    /**
     * Tìm và thực thi Controller/Method tương ứng với Request.
     *
     * @param string $uri
     * @param string $method
     * @throws RouteNotFoundException
     */
    public function dispatch(string $uri, string $method): void
    {
        foreach ($this->routes as $route) {
            // Kiểm tra xem route có khớp với URI và Method không
            if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                
                // Tách chuỗi 'ControllerName@methodName'
                list($controller, $action) = explode('@', $route['controller']);

                // Thêm namespace đầy đủ cho Controller
                // Ví dụ: 'UserController' -> 'App\Controllers\UserController'
                $controllerClass = "App\\Controllers\\" . $controller;

                if (!class_exists($controllerClass)) {
                    throw new \Exception("Controller $controllerClass not found.");
                }

                // Khởi tạo (new) Controller
                $controllerInstance = new $controllerClass();

                if (!method_exists($controllerInstance, $action)) {
                    throw new \Exception("Method $action not found in $controllerClass.");
                }

                // Gọi method của controller
                $controllerInstance->{$action}();
                return; // Dừng lại khi đã tìm thấy route
            }
        }

        // Nếu vòng lặp kết thúc mà không tìm thấy route
        throw new RouteNotFoundException("No route found for URI: $uri");
    }
}