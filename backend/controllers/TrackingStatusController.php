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

        foreach ($trackingStatuses as $trackingStatus) {
            $data[] = $trackingStatus->jsonSerialize();
        }

        $this->jsonResponse($data);
    }

    public function getEntityById(int $id): void
    {
        $trackingStatus = $this->repository->find($id);
        if (!$trackingStatus) {
            $this->notFoundResponse('Tracking status');
            return;
        }

        $this->jsonResponse($trackingStatus->jsonSerialize());
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

        $this->createdResponse('Tracking status');
    }

    public function updateEntity(int $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $trackingStatus = $this->repository->find($id);

        if (!$trackingStatus) {
            $this->notFoundResponse('Tracking status');
            return;
        }

        $success = $this->repository->update($trackingStatus, $data);
        $this->updateResponse('Tracking status', $success);
    }

    public function deleteEntity(int $id): void
    {
        $trackingStatus = $this->repository->find($id);

        if (!$trackingStatus) {
            $this->notFoundResponse('Tracking status');
            return;
        }

        $this->repository->delete($trackingStatus);
        $this->deleteResponse('Tracking status');
    }
}