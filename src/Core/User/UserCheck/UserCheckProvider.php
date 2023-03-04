<?php

declare(strict_types=1);

namespace App\Core\User\UserCheck;

interface UserCheckProvider {

    public function getToken():string|null;
}
