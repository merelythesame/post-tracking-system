<?php

use controllers\{PostOfficeController, ShipmentController, SupportTicketController, TrackingStatusController, UserController };
use middleware\{BufferingMiddleware, Dispatcher};
use routes\{Router, RouterStrategies\AddStrategy, RouterStrategies\DeleteStrategy,
    RouterStrategies\GetCollectionStrategy, RouterStrategies\GetStrategy,
    RouterStrategies\LogInUserStrategy, RouterStrategies\LogOutStrategy, RouterStrategies\UpdateStrategy};
use security\PostOfficeSecurity\AlterPostOfficeSecurity;
use security\ShipmentSecurity\{AlterShipmentSecurity, GetShipmentCollectionSecurity};
use security\SupportTicketSecurity\{AlterTicketSecurity, GetCollectionSupportTicketSecurity, UpdateSupportTicketSecurity};
use security\TrackingStatusSecurity\{AlterTrackingStatusSecurity, GetTrackingStatusCollectionSecurity};
use security\UserSecurity\{AlterUserSecurity, GetUserCollectionSecurity};

session_start();
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/autoload.php';

$router = new Router();

$userController = new UserController();
$postOfficeController = new PostOfficeController();
$shipmentController = new ShipmentController();
$supportTicketController = new SupportTicketController();
$trackingStatusController = new TrackingStatusController();

$getUser = new GetStrategy($userController);
$getUsers = new GetCollectionStrategy($userController);
$addUser = new AddStrategy($userController);
$updateUser = new UpdateStrategy($userController);
$deleteUser = new DeleteStrategy($userController);
$loginUser = new LogInUserStrategy();
$logoutUser = new LogOutStrategy();

$router->register('GET', '#^/users$#', new GetUserCollectionSecurity($getUsers));
$router->register('GET', '#^/users/(\d+)$#', new AlterUserSecurity($getUser));
$router->register('POST', '#^/users$#', $addUser);
$router->register('POST', '#^/login$#', $loginUser);
$router->register('GET', '#^/logout$#', $logoutUser);
$router->register('PATCH', '#^/users/(\d+)$#', new AlterUserSecurity($updateUser));
$router->register('DELETE', '#^/users/(\d+)$#', new AlterUserSecurity($deleteUser));

$getShipment = new GetStrategy($shipmentController);
$getShipments = new GetCollectionStrategy($shipmentController);
$addShipment = new AddStrategy($shipmentController);
$updateShipment = new UpdateStrategy($shipmentController);
$deleteShipment = new DeleteStrategy($shipmentController);

$router->register('GET', '#^/shipments$#', new GetShipmentCollectionSecurity($getShipments));
$router->register('GET', '#^/shipments/(\d+)$#', new AlterShipmentSecurity($getShipment));
$router->register('POST', '#^/shipments#', $addShipment);
$router->register('PATCH', '#^/shipments/(\d+)$#', new AlterShipmentSecurity($updateShipment));
$router->register('DELETE', '#^/shipments/(\d+)$#', new AlterShipmentSecurity($deleteShipment));

$getTrackingStatus = new GetStrategy($trackingStatusController);
$getTrackingStatuses = new GetCollectionStrategy($trackingStatusController);
$addTrackingStatus = new AddStrategy($trackingStatusController);
$updateTrackingStatus = new UpdateStrategy($trackingStatusController);
$deleteTrackingStatus = new DeleteStrategy($trackingStatusController);

$router->register('GET', '#^/tracking-status$#', new GetTrackingStatusCollectionSecurity($getTrackingStatuses));
$router->register('GET', '#^/tracking-status/(\d+)$#', new AlterTrackingStatusSecurity($getTrackingStatus));
$router->register('POST', '#^/tracking-status#', $addTrackingStatus);
$router->register('PATCH', '#^/tracking-status/(\d+)$#', new AlterTrackingStatusSecurity($updateTrackingStatus));
$router->register('DELETE', '#^/tracking-status/(\d+)$#', new AlterTrackingStatusSecurity($deleteTrackingStatus));

$getPostOffice = new GetStrategy($postOfficeController);
$getPostOffices = new GetCollectionStrategy($postOfficeController);
$addPostOffice = new AddStrategy($postOfficeController);
$updatePostOffice = new UpdateStrategy($postOfficeController);
$deletePostOffice = new DeleteStrategy($postOfficeController);

$router->register('GET', '#^/post-office$#', $getPostOffices);
$router->register('GET', '#^/post-office/(\d+)$#', $getPostOffice);
$router->register('POST', '#^/post-office#', new AlterPostOfficeSecurity($addPostOffice));
$router->register('PATCH', '#^/post-office/(\d+)$#', new AlterPostOfficeSecurity($updatePostOffice));
$router->register('DELETE', '#^/post-office/(\d+)$#', new AlterPostOfficeSecurity($deletePostOffice));

$getSupportTicket = new GetStrategy($supportTicketController);
$getSupportTickets = new GetCollectionStrategy($supportTicketController);
$addSupportTicket = new AddStrategy($supportTicketController);
$updateSupportTicket = new UpdateStrategy($supportTicketController);
$deleteSupportTicket = new DeleteStrategy($supportTicketController);

$router->register('GET', '#^/support-tickets$#', new GetCollectionSupportTicketSecurity($getSupportTickets));
$router->register('GET', '#^/support-tickets/(\d+)$#', new AlterTicketSecurity($getSupportTicket));
$router->register('POST', '#^/support-tickets#', $addSupportTicket);
$router->register('PATCH', '#^/support-tickets/(\d+)$#', new UpdateSupportTicketSecurity($updateSupportTicket));
$router->register('DELETE', '#^/support-tickets/(\d+)$#', new AlterTicketSecurity($deleteSupportTicket));


$uri = explode('?', $_SERVER['REQUEST_URI'])[0];
$method = $_SERVER['REQUEST_METHOD'];

[$strategy, $params] = $router->resolve($uri, $method) ?? [null, []];


$finalHandler = function($params) use ($strategy) {
    if ($strategy) {
        $strategy->handle($params);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Not Found']);
    }
};

$dispatcher = new Dispatcher([
    new BufferingMiddleware()
], $finalHandler);

$dispatcher->handle($params);
