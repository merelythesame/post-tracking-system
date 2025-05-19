<?php

namespace repository;

use config\Database;
use models\User;
use PDO;

class UserRepository
{
    public function all(): array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM users");

        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $this->hydrateUser($row);
        }
        return $users;
    }

    public function find(int $id): ?User
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrateUser($row) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrateUser($row) : null;
    }

    public function save(User $user): bool
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("
            INSERT INTO users (name, surname, email, password, phone_number, role)
            VALUES (?, ? ,?, ?, ?, ?)
        ");
        $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        return $stmt->execute([
            $user->getName(),
            $user->getSurname(),
            $user->getEmail(),
            $hashedPassword,
            $user->getPhoneNumber(),
            $user->getRole() ?? User::ROLE_USER,
        ]);
    }

    public function update(User $user, array $fields): bool
    {
        $pdo = Database::getInstance();

        $setClauses = [];
        $values = [];

        foreach ($fields as $key => $value) {
            $setClauses[] = "$key = ?";
            $values[] = $value;
        }

        if (empty($setClauses)) return false;

        $values[] = $user->getId();

        $sql = "UPDATE users SET " . implode(', ', $setClauses) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($values);
    }

    public function delete(User $user): bool
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$user->getId()]);
    }

    private function hydrateUser(array $row): User
    {
        $user = new User();
        $user->setId($row['id']);
        $user->setName($row['name']);
        $user->setSurname($row['surname']);
        $user->setEmail($row['email']);
        $user->setPassword($row['password']);
        $user->setPhoneNumber($row['phone_number']);
        $user->setRole($row['role']);
        return $user;
    }

}