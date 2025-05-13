<?php

namespace config;

use models\User;
use repository\UserRepository;

class Security
{
    private static ?User $instance = null;

    public static function setUser(?User $instance): void
    {
        self::$instance = $instance;
        $_SESSION['user_id'] = $instance->getId();
    }

    public static function getUser(): ?User
    {
        if (self::$instance) {
            return self::$instance;
        }

        if (isset($_SESSION['user_id'])) {
            $repository = new UserRepository();
            self::$instance = $repository->find($_SESSION['user_id']);
            return self::$instance;
        }

        return null;
    }


}