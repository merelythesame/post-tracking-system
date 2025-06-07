<?php

namespace repository;

use PDO;
use models\User;

class UserRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, User::class, 'users');
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo
            ->prepare("SELECT * FROM {$this->tableName} WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    public function save(object $entity): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO {$this->tableName}
              (name, surname, email, password, phone_number, role)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $hashed = password_hash($entity->getPassword(), PASSWORD_DEFAULT);
        $stmt->execute([
            $entity->getName(),
            $entity->getSurname(),
            $entity->getEmail(),
            $hashed,
            $entity->getPhoneNumber(),
            $entity->getRole() ?? User::ROLE_USER,
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function update(object $entity, array $fields): bool
    {
        if (empty($fields)) {
            return false;
        }

        $setClauses = [];
        $values     = [];

        foreach ($fields as $key => $value) {
            if ($key === 'password') {
                // якщо пароль змінився — хешуємо
                if (!password_verify($value, $entity->getPassword())) {
                    $value = password_hash($value, PASSWORD_DEFAULT);
                } else {
                    continue; // пропускаємо, якщо без змін
                }
            }
            $setClauses[] = "$key = ?";
            $values[]     = $value;
        }

        if (empty($setClauses)) {
            return false;
        }

        $values[] = $entity->getId();
        $sql      = "UPDATE {$this->tableName} SET " . implode(', ', $setClauses) . " WHERE id = ?";
        $stmt     = $this->pdo->prepare($sql);

        return $stmt->execute($values);
    }

    protected function hydrate(array $row): object
    {
        $u = new User();
        $u->setId((int)$row['id']);
        $u->setName($row['name']);
        $u->setSurname($row['surname']);
        $u->setEmail($row['email']);
        $u->setPassword($row['password']);
        $u->setPhoneNumber($row['phone_number']);
        $u->setRole($row['role']);

        return $u;
    }
}
