<?php

use controllers\UserController;

require_once __DIR__ . '/../autoload.php';

$uri = explode('?', $_SERVER['REQUEST_URI'])[0];

switch ($uri) {
    case '/users':
        $controller = new UserController();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller->index();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        }
        break;

    case (bool)preg_match('#^/users/(\d+)$#', $uri, $matches):
        $controller = new UserController();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller->show($matches[1]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['message' => 'Not Found']);
        break;
}
