<?php

use routes\Router;
use routes\UserStrategies\AddUserStrategy;
use routes\UserStrategies\DeleteUserStrategy;
use routes\UserStrategies\GetUsersStrategy;
use routes\UserStrategies\GetUserStrategy;
use routes\UserStrategies\LogInUserStrategy;
use routes\UserStrategies\UpdateUserStrategy;

session_start();

require_once __DIR__ . '/../autoload.php';

$router = new Router();

$router->register('GET', '#^/users$#', new GetUsersStrategy());
$router->register('GET', '#^/users/(\d+)$#', new GetUserStrategy());
$router->register('POST', '#^/users$#', new AddUserStrategy());
$router->register('POST', '#^/login$#', new LoginUserStrategy());
$router->register('PATCH', '#^/users/(\d+)$#', new UpdateUserStrategy());
$router->register('DELETE', '#^/users/(\d+)$#', new DeleteUserStrategy());

$uri = explode('?', $_SERVER['REQUEST_URI'])[0];
$method = $_SERVER['REQUEST_METHOD'];

[$strategy, $params] = $router->resolve($uri, $method) ?? [null, []];


if ($strategy) {
    $strategy->handle($params);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Not Found']);
}
