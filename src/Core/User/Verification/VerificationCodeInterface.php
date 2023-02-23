<?php

namespace le7\Core\User\Verification;

interface VerificationCodeInterface {

    public function setCode(string|int $userId, string $code): bool;

    public function getCode(string|int $userId): int|null;

    public function verifyCode(string|int $userId, string $code): bool;

    public function deleteCode(string $code): bool;
}
