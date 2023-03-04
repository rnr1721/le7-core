<?php

namespace App\Core\User\Notifications;

interface NotificationCustomFactory {

    public function getCustomNotification(): NotificationInterface;
}
