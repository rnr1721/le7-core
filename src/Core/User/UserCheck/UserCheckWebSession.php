<?php

declare(strict_types=1);

namespace App\Core\User\UserCheck;

class UserCheckWebSession implements UserCheckProvider {

    public function getToken(): string|null {
        $userToken = $_SESSION['user_token'] ?? null;
        return $userToken;
    }

}
