<?php

namespace models;

require_once __DIR__ . '/../autoload.php';
use config\Database;
use PDO;

class User
{
    const string ROLE_ADMIN = 'ROLE_ADMIN';
    const string ROLE_USER = 'ROLE_USER';
    public int $id;
    public string $name;
    public string $email;
    public string $phoneNumber;
    public string $password;
    public string $role;

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
            $user->password = $row['password'];
            $user->phoneNumber = $row['phone_number'];
            $user->role = $row['role'];

            $users[] = $user;
        }

        return $users;
    }

    public static function findByEmail(string $email): ?User
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $user = new User();
            $user->id = $row['id'];
            $user->email = $row['email'];
            $user->password = $row['password'];
            $user->role = $row['role'];

            return $user;
        }
        return null;
    }

    public static function find(int $id): ?User
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
            $user->password = $row['password'];
            $user->phoneNumber = $row['phone_number'];
            $user->role = $row['role'];

            return $user;
        }

        return null;
    }

    public function save(): bool
    {
        $pdo = Database::getInstance();
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone_number, role) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$this->name, $this->email, $hashedPassword, $this->phoneNumber, User::ROLE_USER]);
    }

    public function update(array $fields): bool
    {
        $pdo = Database::getInstance();

        $setClauses = [];
        $values = [];

        foreach ($fields as $key => $value) {
            if (property_exists($this, $key)) {
                $setClauses[] = "$key = ?";
                $values[] = $value;
            }
        }

        if (empty($setClauses)) {
            return false;
        }

        $values[] = $this->id;

        $sql = "UPDATE users SET " . implode(', ', $setClauses) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute($values);
    }


    public function delete(): bool
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$this->id]);
    }

}