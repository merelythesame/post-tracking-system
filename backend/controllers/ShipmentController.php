<?php

namespace controllers;

use models\Shipment;
use repository\ShipmentRepository;

class ShipmentController
{
    private ShipmentRepository $repository;

    public function __construct()
    {
        $this->repository = new ShipmentRepository();
    }

    public function getShipments(): void
    {
        $data = [];
        $shipments = $this->repository->all();
        header('Content-Type: application/json');
        foreach ($shipments as $shipment) {
            $data[] = $shipment->jsonSerialize();
        }

        echo json_encode($data);
    }

    public function getShipmentsByUser(int $id): void
    {
        $data = [];
        $shipments = $this->repository->findByUserId($id);
        header('Content-Type: application/json');
        foreach ($shipments as $shipment) {
            $data[] = $shipment->jsonSerialize();
        }

        echo json_encode($data);
    }

    public function getShipmentById(int $id): void
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

    public function addShipment(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $shipment = new Shipment();
        $shipment->setUserId($data['user_id']);
        $shipment->setReceiverName($data['receiver_name']);
        $shipment->setAddress($data['address']);
        $shipment->setWeight($data['weight']);
        $shipment->setPrice($data['price']);
        $shipment->setType($data['type']);
        $shipment->setCreatedAt(time());

        $this->repository->save($shipment);
        http_response_code(201);
        echo json_encode(['message' => 'Shipment created']);
    }

    public function updateShipment(int $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $shipment = $this->repository->find($id);

        if (!$shipment) {
            http_response_code(404);
            echo json_encode(['message' => 'Shipment not found']);
            return;
        }

        $success = $this->repository->update($shipment, $data);
        http_response_code($success ? 200 : 400);
        echo json_encode(['message' => $success ? 'Shipment updated' : 'Update failed']);
    }

    public function deleteShipment(int $id): void
    {
        $shipment = $this->repository->find($id);

        if (!$shipment) {
            http_response_code(404);
            echo json_encode(['message' => 'Shipment not found']);
            return;
        }

        $this->repository->delete($shipment);
        http_response_code(202);
        echo json_encode(['message' => 'Shipment deleted']);
    }

}