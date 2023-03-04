<?php

declare(strict_types=1);

namespace App\Core\User;

interface UserCheckInterface {

    /**
     * Get logged in user ID
     * @return int|null
     */
    public function getUserId():int|null;
    
    /**
     * Get logged in user token
     * @return string|null
     */
    public function getToken(): string|null;
}
