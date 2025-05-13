<?php

namespace controllers;
use models\User;

require_once __DIR__ . '/../autoload.php';

class UserController
{
    public function getUsers(): void
    {
        $users = User::all();
        header('Content-Type: application/json');
        echo json_encode($users);
    }

    public function getUserById($id): void
    {
        $user = User::find($id);
        header('Content-Type: application/json');
        echo json_encode($user);
    }

    public function addUser(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->phoneNumber = $data['phoneNumber'];
        $user->save();

        http_response_code(201);
        echo json_encode(['message' => 'User created']);
    }

    public function updateUser($id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !is_array($data)) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input']);
            return;
        }

        $user = User::find($id);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['message' => 'User not found']);
            return;
        }

        $success = $user->update($data);

        if ($success) {
            http_response_code(200);
            echo json_encode(['message' => 'User updated']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'No fields to update or update failed']);
        }
    }

    public function deleteUser($id): void
    {
        $user = User::find($id);
        $user->delete();

        http_response_code(202);
        echo json_encode(['message' => 'User deleted']);
    }

}