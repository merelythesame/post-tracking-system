<?php

namespace controllers;

use models\Shipment;
use repository\ShipmentRepository;

class ShipmentController extends AbstractController implements HasUserEntitiesInterface
{
    public function __construct()
    {
        parent::__construct(new ShipmentRepository());
    }

    public function getAllEntities(): void
    {
        $data = [];
        $shipments = $this->repository->all();

        foreach ($shipments as $shipment) {
            $data[] = $shipment->jsonSerialize();
        }

        $this->jsonResponse($data);
    }

    public function getEntityByUser(int $id): void
    {
        $data = [];
        $shipments = $this->repository->findByUserId($id);

        foreach ($shipments as $shipment) {
            $data[] = $shipment->jsonSerialize();
        }

        $this->jsonResponse($data);
    }

    public function getEntityById(int $id): void
    {
        $shipment = $this->repository->find($id);
        if (!$shipment) {
            $this->notFoundResponse('Shipment');
            return;
        }

        $this->jsonResponse($shipment->jsonSerialize());
    }

    public function addEntity(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $shipment = new Shipment();
        $shipment->setUserId($data['user_id']);
        $shipment->setReceiverName($data['receiver_name']);
        $shipment->setSenderName($data['sender_name']);
        $shipment->setReceiverId($data['receiver_id'] ?? null);
        $shipment->setWeight($data['weight']);
        $shipment->setType($data['type']);
        $shipment->setCreatedAt(time());
        $shipment->setSendOffice($data['send_office']);
        $shipment->setReceiveOffice($data['receive_office']);

        $shipmentId = $this->repository->save($shipment);
        $this->createdResponse('Shipment', $shipmentId);
    }

    public function updateEntity(int $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $shipment = $this->repository->find($id);

        if (!$shipment) {
            $this->notFoundResponse('Shipment');
            return;
        }

        $success = $this->repository->update($shipment, $data);
        $this->updateResponse('Shipment', $success);
    }

    public function deleteEntity(int $id): void
    {
        $shipment = $this->repository->find($id);

        if (!$shipment) {
            $this->notFoundResponse('Shipment');
            return;
        }

        $this->repository->delete($shipment);
        $this->deleteResponse('Shipment');
    }
}