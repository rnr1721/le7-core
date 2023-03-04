<?php

namespace App\Core\User\Notifications;

interface NotificationsInterface {

    public function getBroadcast(array|string $cases = []): array;

    public function sendMessage(array $options, array|string $cases = []): bool;
    
    public function getErrors():array;
}
