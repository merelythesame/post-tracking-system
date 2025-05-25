<?php

use routes\PostOfficeStrategies\AddPostOfficeStrategy;
use routes\PostOfficeStrategies\DeletePostOfficeStrategy;
use routes\PostOfficeStrategies\GetCollectionPostOfficeStrategy;
use routes\PostOfficeStrategies\GetPostOfficeStrategy;
use routes\PostOfficeStrategies\UpdatePostOfficeStrategy;
use routes\Router;
use routes\ShipmentStrategies\AddShipmentStrategy;
use routes\ShipmentStrategies\DeleteShipmentStrategy;
use routes\ShipmentStrategies\GetShipmentCollectingStrategy;
use routes\ShipmentStrategies\GetShipmentStrategy;
use routes\ShipmentStrategies\UpdateShipmentStrategy;
use routes\TrackingStatusStrategies\AddTrackingStatusStrategy;
use routes\TrackingStatusStrategies\DeleteTrackingStatusStrategy;
use routes\TrackingStatusStrategies\GetTrackingStatusCollectionStrategy;
use routes\TrackingStatusStrategies\GetTrackingStatusStrategy;
use routes\TrackingStatusStrategies\UpdateTrackingStatusStrategy;
use routes\UserStrategies\AddUserStrategy;
use routes\UserStrategies\DeleteUserStrategy;
use routes\UserStrategies\GetUserCollectionStrategy;
use routes\UserStrategies\GetUserStrategy;
use routes\UserStrategies\LogInUserStrategy;
use routes\UserStrategies\UpdateUserStrategy;
use security\PostOfficeSecurity\AlterPostOfficeSecurity;
use security\ShipmentSecurity\DeleteShipmentSecurity;
use security\ShipmentSecurity\GetShipmentCollectionSecurity;
use security\ShipmentSecurity\GetShipmentSecurity;
use security\ShipmentSecurity\UpdateShipmentSecurity;
use security\TrackingStatusSecurity\DeleteTrackingStatusSecurity;
use security\TrackingStatusSecurity\GetTrackingStatusCollectionSecurity;
use security\TrackingStatusSecurity\GetTrackingStatusSecurity;
use security\TrackingStatusSecurity\UpdateTrackingStatusSecurity;
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

$router->register('GET', '#^/shipments$#', new GetShipmentCollectionSecurity(new GetShipmentCollectingStrategy()));
$router->register('GET', '#^/shipments/(\d+)$#', new GetShipmentSecurity(new GetShipmentStrategy()));
$router->register('POST', '#^/shipments#', new AddShipmentStrategy());
$router->register('PATCH', '#^/shipments/(\d+)$#', new UpdateShipmentSecurity(new UpdateShipmentStrategy()));
$router->register('DELETE', '#^/shipments/(\d+)$#', new DeleteShipmentSecurity(new DeleteShipmentStrategy()));

$router->register('GET', '#^/tracking-status$#', new GetTrackingStatusCollectionSecurity(new GetTrackingStatusCollectionStrategy()));
$router->register('GET', '#^/tracking-status/(\d+)$#', new GetTrackingStatusSecurity(new GetTrackingStatusStrategy()));
$router->register('POST', '#^/tracking-status#', new AddTrackingStatusStrategy());
$router->register('PATCH', '#^/tracking-status/(\d+)$#', new UpdateTrackingStatusSecurity(new UpdateTrackingStatusStrategy()));
$router->register('DELETE', '#^/tracking-status/(\d+)$#', new DeleteTrackingStatusSecurity(new DeleteTrackingStatusStrategy()));

$router->register('GET', '#^/post-office$#', new GetCollectionPostOfficeStrategy());
$router->register('GET', '#^/post-office/(\d+)$#', new GetPostOfficeStrategy());
$router->register('POST', '#^/post-office#', new AlterPostOfficeSecurity(new AddPostOfficeStrategy()));
$router->register('PATCH', '#^/post-office/(\d+)$#', new AlterPostOfficeSecurity(new UpdatePostOfficeStrategy()));
$router->register('DELETE', '#^/post-office/(\d+)$#', new AlterPostOfficeSecurity(new DeletePostOfficeStrategy()));

$uri = explode('?', $_SERVER['REQUEST_URI'])[0];
$method = $_SERVER['REQUEST_METHOD'];

[$strategy, $params] = $router->resolve($uri, $method) ?? [null, []];


if ($strategy) {
    $strategy->handle($params);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Not Found']);
}
