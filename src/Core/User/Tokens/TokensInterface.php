<?php

declare(strict_types=1);

namespace App\Core\User\Tokens;

interface TokensInterface {

    public function getUserId(string $token): int|null;

    public function create(int $userId, string $info = ''): string|null;

    public function delete(string $token): bool;

    public function getAll(int $userId): array;

    public function deleteAll(int $userId): bool;
}
