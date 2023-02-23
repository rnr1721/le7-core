<?php

namespace le7\Core\User;

interface UserLoginInterface {

    public function login(array $user, string $password);

    public function logout();

    public function getErrors(): array;
}
