<?php

namespace controllers;

use models\PostOffice;
use repository\PostOfficeRepository;

class PostOfficeController extends AbstractController
{
    public function __construct()
    {
        parent::__construct(new PostOfficeRepository());
    }

    public function getAllEntities(): void
    {
        $data = [];
        $offices = $this->repository->all();

        foreach ($offices as $office) {
            $data[] = $office->jsonSerialize();
        }

        $this->jsonResponse($data);
    }

    public function getEntityById(int $id): void
    {
        $office = $this->repository->find($id);
        if (!$office) {
            $this->notFoundResponse('Post office');
            return;
        }

        $this->jsonResponse($office->jsonSerialize());
    }

    public function addEntity(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $office = new PostOffice();
        $office->setName($data['name']);
        $office->setAddress($data['address']);
        $office->setCity($data['city']);
        $office->setPostalCode($data['postal_code']);

        $this->repository->save($office);
        $this->createdResponse('Post office');
    }

    public function updateEntity(int $id): void
    {
        $office = $this->repository->find($id);
        if (!$office) {
            $this->notFoundResponse('Post office');
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $success = $this->repository->update($office, $data);
        $this->updateResponse('Post office', $success);
    }

    public function deleteEntity(int $id): void
    {
        $office = $this->repository->find($id);
        if (!$office) {
            $this->notFoundResponse('Post office');
            return;
        }

        $this->repository->delete($office);
        $this->deleteResponse('Post office');
    }
}