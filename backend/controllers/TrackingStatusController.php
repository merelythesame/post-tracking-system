<?php

namespace controllers;

use models\TrackingStatus;
use repository\TrackingStatusRepository;

class TrackingStatusController
{
    private TrackingStatusRepository $repository;

    public function __construct()
    {
        $this->repository = new TrackingStatusRepository();
    }

    public function getTrackingStatuses(): void
    {
        $data = [];
        $trackingStatuses = $this->repository->all();
        header('Content-Type: application/json');
        foreach ($trackingStatuses as $trackingStatus) {
            $data[] = $trackingStatus->jsonSerialize();
        }

        echo json_encode($data);
    }

    public function getTrackingStatusById(int $id): void
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

    public function addTrackingStatus(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $trackingStatus = new TrackingStatus();
        $trackingStatus->setShipmentId($data['shipmentId']);
        $trackingStatus->setStatus($data['status']);
        $trackingStatus->setLocation($data['location']);

        if(in_array('sendAt', $data)){
            $trackingStatus->setSendAt($data['sendAt']);
        }
        else{
            $trackingStatus->setSendAt(null);
        }

        if(in_array('arriveAt', $data)){
            $trackingStatus->setArriveAt($data['arriveAt']);
        }
        else{
            $trackingStatus->setArriveAt(null);
        }

        $this->repository->save($trackingStatus);
        http_response_code(201);
        echo json_encode(['message' => 'Tracking status created']);
    }

    public function updateTrackingStatus(int $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $trackingStatus = $this->repository->find($id);

        if (!$trackingStatus) {
            http_response_code(404);
            echo json_encode(['message' => 'Tracking status not found']);
            return;
        }

        $success = $this->repository->update($trackingStatus, $data);
        http_response_code($success ? 200 : 400);
        echo json_encode(['message' => $success ? 'Tracking status updated' : 'Update failed']);
    }

    public function deleteTrackingStatus(int $id): void
    {
        $trackingStatus = $this->repository->find($id);

        if (!$trackingStatus) {
            http_response_code(404);
            echo json_encode(['message' => 'Tracking status not found']);
            return;
        }

        $this->repository->delete($trackingStatus);
        http_response_code(202);
        echo json_encode(['message' => 'Tracking status deleted']);
    }

}