<?php

namespace controllers;

use repository\RepositoryInterface;

abstract class AbstractController
{
    protected RepositoryInterface $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    abstract public function getAllEntities(): void;
    abstract public function getEntityById(int $id): void;
    abstract public function addEntity(): void;
    abstract public function updateEntity(int $id): void;
    abstract public function deleteEntity(int $id): void;


    protected function jsonResponse(array $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
    }


    protected function notFoundResponse(string $entityName): void
    {
        $this->jsonResponse(['message' => $entityName . ' not found'], 404);
    }


    protected function createdResponse(string $entityName, ?int $id = null): void
    {
        $response = ['message' => $entityName . ' created'];
        if ($id !== null) {
            $response['id'] = $id;
        }
        $this->jsonResponse($response, 201);
    }


    protected function updateResponse(string $entityName, bool $success): void
    {
        $this->jsonResponse(
            ['message' => $success ? $entityName . ' updated' : 'Update failed'],
            $success ? 200 : 400
        );
    }


    protected function deleteResponse(string $entityName): void
    {
        $this->jsonResponse(['message' => $entityName . ' deleted'], 202);
    }
}