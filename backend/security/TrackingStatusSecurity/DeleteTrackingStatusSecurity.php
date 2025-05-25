<?php

namespace security\TrackingStatusSecurity;

use config\Security;
use models\User;
use repository\ShipmentRepository;
use repository\TrackingStatusRepository;
use security\SecurityDecorator;

class DeleteTrackingStatusSecurity extends SecurityDecorator
{
    public function handle(array $params = []): void
    {
        $currentUser = Security::getUser();

        if(!$currentUser){
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized - please log in.']);
            return;
        }

        $shipmentRepository = new ShipmentRepository();
        $trackingStatusRepository = new TrackingStatusRepository();
        $trackingStatus = $trackingStatusRepository->find($params[0]);
        $shipment = $shipmentRepository->find($trackingStatus->getShipmentId());

        if(!$shipment){
            http_response_code(404);
            echo json_encode(['message' => 'Shipment not found.']);
            return;
        }

        if(($currentUser->getRole() === User::ROLE_USER and $currentUser->getId() == $shipment->getUserId()) or $currentUser->getRole() === User::ROLE_ADMIN){
            parent::handle($params);
            return;
        }

        http_response_code(403);
        echo json_encode(['message' => 'Forbidden - you do not have access.']);

    }

}