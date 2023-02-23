<?php

namespace le7\Core\User\Notifications;

interface NotificationEmailFactoryInterface {

    public function getMailer(): NotificationEmailInterface;

    public function getSystemMailer(): NotificationEmailInterface|NotificationInterface;
}
