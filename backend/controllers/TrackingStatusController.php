<?php

namespace controllers;

use models\TrackingStatus;
use repository\TrackingStatusRepository;

class TrackingStatusController extends AbstractController
{
    public function __construct()
    {
        parent::__construct(new TrackingStatusRepository());
    }

    public function getAllEntities(): void
    {
        $data = [];
        $trackingStatuses = $this->repository->all();
        header('Content-Type: application/json');
        foreach ($trackingStatuses as $trackingStatus) {
            $data[] = $trackingStatus->jsonSerialize();
        }

        echo json_encode($data);
    }

    public function getEntityById(int $id): void
    {
        $trackingStatus = $this->repository->find($id);
        if (!$trackingStatus) {
            http_response_code(404);
            echo json_encode(['message' => 'Tracking status not found']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($trackingStatus->jsonSerialize());
    }

    public function addEntity(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $trackingStatus = new TrackingStatus();

        $trackingStatus->setShipmentId($data['shipment_id']);
        $trackingStatus->setStatus($data['status'] ?? 'pending');
        $trackingStatus->setLocation($data['location'] ?? '');
        $trackingStatus->setSendAt($data['send_at'] ?? null);
        $trackingStatus->setArriveAt($data['arrive_at'] ?? null);

        $this->repository->save($trackingStatus);

        header('Content-Type: application/json');
        http_response_code(201);
        echo json_encode(['message' => 'Tracking status created']);
    }


    public function updateEntity(int $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $trackingStatus = $this->repository->find($id);

        if (!$trackingStatus) {
            http_response_code(404);
            echo json_encode(['message' => 'Tracking status not found']);
            return;
        }

        $success = $this->repository->update($trackingStatus, $data);
        header('Content-Type: application/json');
        http_response_code($success ? 200 : 400);
        echo json_encode(['message' => $success ? 'Tracking status updated' : 'Update failed']);
    }

    public function deleteEntity(int $id): void
    {
        $trackingStatus = $this->repository->find($id);

        if (!$trackingStatus) {
            http_response_code(404);
            echo json_encode(['message' => 'Tracking status not found']);
            return;
        }

        $this->repository->delete($trackingStatus);
        header('Content-Type: application/json');
        http_response_code(202);
        echo json_encode(['message' => 'Tracking status deleted']);
    }

}