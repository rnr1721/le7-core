<?php

declare(strict_types=1);

namespace le7\Core\User\Tokens;

interface TokensInterface {

    public function getUserId(string $token): int|null;

    public function create(int $userId): string|null;

    public function delete(string $token): bool;

    public function getAll(int $userId): array;

    public function deleteAll(int $userId): bool;
}
