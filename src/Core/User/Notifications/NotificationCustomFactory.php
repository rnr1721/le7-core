<?php

namespace le7\Core\User\Notifications;

interface NotificationCustomFactory {

    public function getCustomNotification(): NotificationInterface;
}
