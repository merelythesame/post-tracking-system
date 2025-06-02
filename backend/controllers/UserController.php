<?php

namespace controllers;

use config\Security;
use http\Message;
use models\User;
use repository\UserRepository;

class UserController extends AbstractController
{
    public function __construct()
    {
        parent::__construct(new UserRepository());
    }

    public function getAllEntities(): void
    {
        $users = $this->repository->all();
        $data = array_map(fn($user) => $user->jsonSerialize(), $users);
        $this->jsonResponse($data);
    }

    public function getEntityById(int $id): void
    {
        $user = $this->repository->find($id);
        if (!$user) {
            $this->jsonResponse(['error' => 'User not found'], 404);
            return;
        }
        $this->jsonResponse($user->jsonSerialize());
    }

    public function getEntityByEmail(string $email): void
    {
        $user = $this->repository->findByEmail($email);
        if (!$user) {
            $this->jsonResponse(['error' => 'User not found'], 404);
            return;
        }
        $this->jsonResponse($user->jsonSerialize());
    }

    public function addEntity(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $user = new User();
        $user->setName($data['name']);
        $user->setSurname($data['surname']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setPhoneNumber($data['phoneNumber']);

        $this->repository->save($user);
        $this->jsonResponse(['message' => 'User created'], 201);
    }

    public function updateEntity(int $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $user = $this->repository->find($id);

        if (!$user) {
            $this->jsonResponse(['error' => 'User not found'], 404);
            return;
        }

        $success = $this->repository->update($user, $data);
        $this->jsonResponse(
            ['message' => $success ? 'User updated' : 'Update failed'],
            $success ? 200 : 400
        );
    }

    public function deleteEntity(int $id): void
    {
        $user = $this->repository->find($id);

        if (!$user) {
            $this->jsonResponse(['error' => 'User not found'], 404);
            return;
        }

        $this->repository->delete($user);
        $this->jsonResponse(['message' => 'User deleted'], 202);
    }

    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            $this->jsonResponse(['error' => 'Invalid input'], 400);
            return;
        }

        $user = $this->repository->findByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user->getPassword())) {
            $this->jsonResponse(['error' => 'Invalid credentials'], 401);
            return;
        }

        Security::setUser($user);
        $this->jsonResponse([
            'message' => 'Successfully logged in',
            'id' => $user->getId(),
            'role' => $user->getRole()
        ]);
    }

    public function logout(): void
    {
        Security::logout();
        $this->jsonResponse(['message' => 'Logged out successfully']);
    }

    private function jsonResponse(array $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
    }
}
