<?php

namespace controllers;
use models\User;

require_once __DIR__ . '/../autoload.php';

class UserController
{
    public function index()
    {
        $users = User::all();
        header('Content-Type: application/json');
        echo json_encode($users);
    }

    public function show($id)
    {
        $user = User::find($id);
        header('Content-Type: application/json');
        echo json_encode($user);
    }

    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        http_response_code(201);
        echo json_encode(['message' => 'User created']);
    }
}