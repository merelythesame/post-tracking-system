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
        header('Content-Type: application/json');
        foreach ($shipments as $shipment) {
            $data[] = $shipment->jsonSerialize();
        }

        echo json_encode($data);
    }

    public function getEntityByUser(int $id): void
    {
        $data = [];
        $shipments = $this->repository->findByUserId($id);
        header('Content-Type: application/json');
        foreach ($shipments as $shipment) {
            $data[] = $shipment->jsonSerialize();
        }

        echo json_encode($data);
    }

    public function getEntityById(int $id): void
    {
        $shipment = $this->repository->find($id);
        if (!$shipment) {
            http_response_code(404);
            echo json_encode(['message' => 'Shipment not found']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($shipment->jsonSerialize());
    }

    public function addEntity(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $shipment = new Shipment();
        $shipment->setUserId($data['user_id']);
        $shipment->setReceiverName($data['receiverName']);
        $shipment->setSenderName($data['senderName']);
        $shipment->setReceiverId($data['receiverId'] ?? null);
        $shipment->setAddress($data['address']);
        $shipment->setWeight($data['weight']);
        $shipment->setType($data['type']);
        $shipment->setCreatedAt(time());

        $shipmentId = $this->repository->save($shipment);
        header('Content-Type: application/json');
        http_response_code(201);
        echo json_encode(['message' => 'Shipment created', 'id' => $shipmentId]);
    }

    public function updateEntity(int $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $shipment = $this->repository->find($id);

        if (!$shipment) {
            http_response_code(404);
            echo json_encode(['message' => 'Shipment not found']);
            return;
        }

        $success = $this->repository->update($shipment, $data);
        header('Content-Type: application/json');
        http_response_code($success ? 200 : 400);
        echo json_encode(['message' => $success ? 'Shipment updated' : 'Update failed']);
    }

    public function deleteEntity(int $id): void
    {
        $shipment = $this->repository->find($id);

        if (!$shipment) {
            http_response_code(404);
            echo json_encode(['message' => 'Shipment not found']);
            return;
        }

        $this->repository->delete($shipment);
        header('Content-Type: application/json');
        http_response_code(202);
        echo json_encode(['message' => 'Shipment deleted']);
    }

}