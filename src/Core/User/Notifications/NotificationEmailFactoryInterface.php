<?php

namespace App\Core\User\Notifications;

interface NotificationEmailFactoryInterface {

    public function getMailer(): NotificationEmailInterface;

    public function getSystemMailer(): NotificationEmailInterface|NotificationInterface;
}
