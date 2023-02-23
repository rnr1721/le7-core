<?php

declare(strict_types=1);

namespace le7\Core\User\UserCheck;

interface UserCheckProvider {

    public function getToken():string|null;
}
