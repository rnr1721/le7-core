<?php

namespace App\Core\User\Notifications;

use App\Core\Config\ConfigInterface;
use \Exception;

class Notifications implements NotificationsInterface {

    private ConfigInterface $config;
    private array $errors = [];

    public function __construct(ConfigInterface $config) {
        $this->config = $config;
    }

    /**
     * Get array with elements that implements NotificationInterface
     * @param array|string $cases For example: email,sms,etc...
     * @return array
     * @throws Exception
     */
    public function getBroadcast(array|string $cases = []): array {
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

    /**
     * Send message using all available methods
     * @param array $options array of options for delivery methods
     * @param array|string $cases email,sms,telegram etc...
     * @return bool
     */
    public function sendMessage(array $options, array|string $cases = []): bool {
        $broadcast = $this->getBroadcast($cases);
        foreach ($broadcast as $case) {
            $case->setOptions($options)->send();
            $this->errors = array_merge($this->errors, $case->getErrors());
        }
        if (count($this->errors) === 0) {
            return true;
        }
        return false;
    }

    /**
     * Get errors array
     * @return array
     */
    public function getErrors(): array {
        return $this->errors;
    }

}
