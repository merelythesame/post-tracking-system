<?php

namespace security\ShipmentSecurity;

use repository\ShipmentRepository;
use security\AbstractSecurity;

class AlterShipmentSecurity extends AbstractSecurity
{
    public function handle(array $params = []): void
    {
        $user = $this->isAuthenticated();
        if (!$user) {
            $this->errorResponse(401, 'Unauthorized - please log in.');
            return;
        }

        $repository = new ShipmentRepository();
        $shipment = $repository->find($params[0]);

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