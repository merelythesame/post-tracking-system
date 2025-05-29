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
        $data = [];
        $users = $this->repository->all();
        header('Content-Type: application/json');
        foreach ($users as $user) {
            $data[] = $user->jsonSerialize();
        }

        echo json_encode($data);
    }

    public function getEntityById(int $id): void
    {
        $user = $this->repository->find($id);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['message' => 'User not found']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($user->jsonSerialize());
    }

    public function getEntityByEmail(string $email): void
    {
        $user = $this->repository->findByEmail($email);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['message' => 'User not found']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($user->jsonSerialize());
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
        header('Content-Type: application/json');
        http_response_code(201);
        echo json_encode(['message' => 'User created']);
    }

    public function updateEntity(int $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $user = $this->repository->find($id);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['message' => 'User not found']);
            return;
        }

        $success = $this->repository->update($user, $data);
        header('Content-Type: application/json');
        http_response_code($success ? 200 : 400);
        echo json_encode(['message' => $success ? 'User updated' : 'Update failed']);
    }

    public function deleteEntity(int $id): void
    {
        $user = $this->repository->find($id);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['message' => 'User not found']);
            return;
        }

        $this->repository->delete($user);
        header('Content-Type: application/json');
        http_response_code(202);
        echo json_encode(['message' => 'User deleted']);
    }

    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['Error' => 'Invalid input']);
            return;
        }

        $user = $this->repository->findByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user->getPassword())) {
            http_response_code(401);
            echo json_encode(['Error' => 'Invalid credentials']);
            return;
        }

        Security::setUser($user);
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode(['message' => 'Successfully logged in', 'id' => $user->getId(), 'role' => $user->getRole()]);
    }

    public function logout(): void
    {
        Security::logout();
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Logged out successfully.']);
    }

}