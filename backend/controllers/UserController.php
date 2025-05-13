<?php

namespace controllers;
use config\Security;
use models\User;
use repository\UserRepository;

require_once __DIR__ . '/../autoload.php';

class UserController
{
    private UserRepository $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function getUsers(): void
    {
        $data = [];
        $users = $this->repository->all();
        header('Content-Type: application/json');
        foreach ($users as $user) {
            $data[] = $user->jsonSerialize();
        }

        echo json_encode($data);
    }

    public function getUserById(int $id): void
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

    public function addUser(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $user = new User();
        $user->setName($data['name']);
        $user->setSurname($data['surname']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setPhoneNumber($data['phoneNumber']);

        $this->repository->save($user);
        http_response_code(201);
        echo json_encode(['message' => 'User created']);
    }

    public function updateUser(int $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $user = $this->repository->find($id);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['message' => 'User not found']);
            return;
        }

        $success = $this->repository->update($user, $data);
        http_response_code($success ? 200 : 400);
        echo json_encode(['message' => $success ? 'User updated' : 'Update failed']);
    }

    public function deleteUser(int $id): void
    {
        $user = $this->repository->find($id);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['message' => 'User not found']);
            return;
        }

        $this->repository->delete($user);
        http_response_code(202);
        echo json_encode(['message' => 'User deleted']);
    }

    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input']);
            return;
        }

        $user = $this->repository->findByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user->getPassword())) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid credentials']);
            return;
        }

        Security::setUser($user);
        http_response_code(200);
        echo json_encode(['message' => 'Successfully logged in']);
    }

}