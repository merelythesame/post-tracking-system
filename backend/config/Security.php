<?php

namespace config;

use models\User;

class Security
{
    private static ?User $instance = null;

    public static function setUser(?User $instance): void
    {
        self::$instance = $instance;
        $_SESSION['user_id'] = $instance->id;
    }

    public static function getUser(): ?User
    {
        if(self::$instance){
            return self::$instance;
        }

        if(isset($_SESSION['user_id'])) {
            return User::find($_SESSION['user_id']);
        }

        return null;
    }

    public static function isAuthenticated(): bool {
        return self::getUser() !== null;
    }


}