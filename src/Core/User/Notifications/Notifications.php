<?php

namespace le7\Core\User\Notifications;

use le7\Core\Config\ConfigInterface;
use \Exception;

class Notifications {

    public ConfigInterface $config;

    public function __construct(ConfigInterface $config) {
        $this->config = $config;
    }

    public function getBroadcast(array|string $cases): array {
        $broadcast = array();
        if (empty($cases)) {
            $cases = explode(',', $this->config->getNotificationCases());
        } else {
            if (is_string($cases)) {
                $cases = explode(',', $cases);
            }
        }
        $factoryClasses = $this->config->getNotificationClasses();
        foreach ($factoryClasses as $factoryKey => $factoryClass) {
            if (in_array($factoryKey, $cases)) {
                $notifications = new $factoryClass($this->config);
                if (!$notifications instanceof NotificationCustomFactory) {
                    throw new Exception(_('Class must be instance of NotificationCustomFactory:') . ' ' . $factoryClass);
                }
                $broadcast[] = $notifications->getCustomNotification();
            }
        }
        return $broadcast;
    }

}
