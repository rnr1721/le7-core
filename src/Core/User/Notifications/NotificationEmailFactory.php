<?php

declare(strict_types=1);

namespace le7\Core\User\Notifications;

use le7\Core\Config\ConfigInterface;
use \PHPMailer\PHPMailer\PHPMailer;

class NotificationEmailFactory implements NotificationCustomFactory, NotificationEmailFactoryInterface {

    private ConfigInterface $config;

    public function __construct(ConfigInterface $config) {
        $this->config = $config;
    }

    /**
     * Get "clean" mailer without settings for manual config
     * @return NotificationEmailInterface
     */
    public function getMailer(): NotificationEmailInterface {
        return new NotificationEmail(new PHPMailer());
    }

    /**
     * Get pre-configured mailer with config from config.ini
     * @return NotificationEmailInterface
     */
    public function getSystemMailer(): NotificationEmailInterface|NotificationInterface {
        $mailer = new NotificationEmail(new PHPMailer());
        $config = $this->config->getEmailConfig();
        $mailer->setHost($config['host'])
                ->setPort($config['port'])
                ->setSecure($config['secure'])
                ->setUsername($config['username'])
                ->setPassword($config['password']);
        $mailer->setFromName($config['from_name'])
                ->setFromEmail($config['from_email'])
                ->setDebug(intval($config['debug']))
                ->setCharset($config['charset'])
                ->setIsHtml((bool) $config['html']);
        return $mailer;
    }

    public function getCustomNotification(): NotificationInterface {
        return $this->getSystemMailer();
    }

}
