<?php

namespace le7\Core\User;

interface UserInterface {

    public function can(string $role): bool;

    public function getRoles(): array;

    public function getUsername(): string;

    public function getIsActive(): bool;
}
