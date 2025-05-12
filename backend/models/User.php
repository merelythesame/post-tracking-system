<?php

namespace models;

require_once __DIR__ . '/../autoload.php';
use config\Database;
use PDO;

class User
{
    public int $id;
    public string $name;
    public string $email;

    public static function all(): array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM users");

        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user = new User();
            $user->id = $row['id'];
            $user->name = $row['name'];
            $user->email = $row['email'];
            $users[] = $user;
        }

        return $users;
    }

    public static function find($id): ?User
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $user = new User();
            $user->id = $row['id'];
            $user->name = $row['name'];
            $user->email = $row['email'];
            return $user;
        }

        return null;
    }

    public function save(): bool
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        return $stmt->execute([$this->name, $this->email]);
    }

}