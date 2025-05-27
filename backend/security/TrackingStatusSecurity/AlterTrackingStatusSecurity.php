<?php

namespace security\TrackingStatusSecurity;

use repository\ShipmentRepository;
use repository\TrackingStatusRepository;
use security\AbstractSecurity;

class AlterTrackingStatusSecurity extends AbstractSecurity
{
    public function handle(array $params = []): void
    {
        $user = $this->isAuthenticated();
        if (!$user) {
            $this->errorResponse(401, 'Unauthorized - please log in.');
            return;
        }

        $shipmentRepository = new ShipmentRepository();
        $trackingStatusRepository = new TrackingStatusRepository();
        $trackingStatus = $trackingStatusRepository->find($params[0]);

        if (!$trackingStatus) {
            $this->errorResponse(404, 'Status not found.');
            return;
        }

        $shipment = $shipmentRepository->find($trackingStatus->getShipmentId());

        if (!$shipment) {
            $this->errorResponse(404, 'Shipment not found.');
            return;
        }

        $isOwner = $this->isUser($user) && $this->isOwner($user, $shipment->getUserId());
        $isAdmin = $this->isAdmin($user);

        if ($isOwner || $isAdmin) {
            parent::handle($params);
        } else {
            $this->errorResponse(403, 'Forbidden - you do not have access.');
        }

    }

}