<?php

declare(strict_types=1);

namespace App\Core\User\Notifications;

interface NotificationInterface {

    public function setOptions(array $options):self;
    
    public function setBody(string $body):self;

    public function send(): bool;

    public function getErrors(): array;
}
