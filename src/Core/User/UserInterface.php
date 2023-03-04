<?php

namespace App\Core\User;

interface UserInterface {

    public function can(string $role): bool;

    public function getRoles(): array;

    public function getUsername(): string;

    public function getIsActive(): bool;
}
