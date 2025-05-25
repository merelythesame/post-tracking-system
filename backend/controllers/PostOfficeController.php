<?php

namespace controllers;

use models\PostOffice;
use repository\PostOfficeRepository;

class PostOfficeController
{
    private PostOfficeRepository $repository;

    public function __construct()
    {
        $this->repository = new PostOfficeRepository();
    }

    public function getPostOffices(): void
    {
        $data = [];
        $offices = $this->repository->all();
        header('Content-Type: application/json');

        foreach ($offices as $office) {
            $data[] = $office->jsonSerialize();
        }

        echo json_encode($data);
    }

    public function getPostOfficeById(int $id): void
    {
        $office = $this->repository->find($id);
        if (!$office) {
            http_response_code(404);
            echo json_encode(['message' => 'Post office not found']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($office->jsonSerialize());
    }

    public function addOffice(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $office = new PostOffice();
        $office->setName($data['name']);
        $office->setAddress($data['address']);
        $office->setCity($data['city']);
        $office->setPostalCode($data['postalCode']);

        $this->repository->save($office);
        http_response_code(201);
        echo json_encode(['message' => 'Post office created']);
    }

    public function updateOffice(int $id): void
    {
        $office = $this->repository->find($id);
        if (!$office) {
            http_response_code(404);
            echo json_encode(['message' => 'Post office not found']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $success = $this->repository->update($office, $data);
        http_response_code($success ? 200 : 400);
        echo json_encode(['message' => $success ? 'Post office updated' : 'Update failed']);
    }

    public function deleteOffice(int $id): void
    {
        $office = $this->repository->find($id);
        if (!$office) {
            http_response_code(404);
            echo json_encode(['message' => 'Post office not found']);
            return;
        }

        $this->repository->delete($office);
        http_response_code(202);
        echo json_encode(['message' => 'Post office deleted']);
    }

}