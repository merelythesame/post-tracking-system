<?php

use routes\Router;
use routes\UserStrategies\AddUserStrategy;
use routes\UserStrategies\DeleteUserStrategy;
use routes\UserStrategies\GetUserCollectionStrategy;
use routes\UserStrategies\GetUserStrategy;
use routes\UserStrategies\LogInUserStrategy;
use routes\UserStrategies\UpdateUserStrategy;
use security\UserSecurity\DeleteUserSecurity;
use security\UserSecurity\GetUserCollectionSecurity;
use security\UserSecurity\GetUserSecurity;
use security\UserSecurity\UpdateUserSecurity;

session_start();

require_once __DIR__ . '/../autoload.php';

$router = new Router();

$router->register('GET', '#^/users$#', new GetUserCollectionSecurity(new GetUserCollectionStrategy()));
$router->register('GET', '#^/users/(\d+)$#', new GetUserSecurity(new GetUserStrategy()));
$router->register('POST', '#^/users$#', new AddUserStrategy());
$router->register('POST', '#^/login$#', new LoginUserStrategy());
$router->register('PATCH', '#^/users/(\d+)$#', new UpdateUserSecurity(new UpdateUserStrategy()));
$router->register('DELETE', '#^/users/(\d+)$#', new DeleteUserSecurity(new DeleteUserStrategy()));

$uri = explode('?', $_SERVER['REQUEST_URI'])[0];
$method = $_SERVER['REQUEST_METHOD'];

[$strategy, $params] = $router->resolve($uri, $method) ?? [null, []];


if ($strategy) {
    $strategy->handle($params);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Not Found']);
}
