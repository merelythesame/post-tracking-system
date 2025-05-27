<?php

namespace security;

use config\Security;
use models\User;

class AbstractSecurity extends SecurityDecorator
{
    protected function isAuthenticated(): ?User
    {
        $user = Security::getUser();
        return $user ?: null;
    }

    protected function isAdmin(User $user): bool
    {
        return $user->getRole() === User::ROLE_ADMIN;
    }

    protected function isUser(User $user): bool
    {
        return $user->getRole() === User::ROLE_USER;
    }

    protected function isOwner(User $user, int $ownerId): bool
    {
        return $user->getId() === $ownerId;
    }

    protected function errorResponse(int $code, string $message): void
    {
        if (!headers_sent()) {
            http_response_code($code);
        }
        echo json_encode(['message' => $message]);
    }

}