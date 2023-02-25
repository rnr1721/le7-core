<?php

namespace le7\Core\User;

interface UserLoginInterface {

    public function login(array $user, string $password):string|null;

    public function logout():bool;

    public function getErrors(): array;
}
