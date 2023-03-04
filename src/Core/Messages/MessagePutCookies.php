<?php

declare(strict_types=1);

namespace App\Core\Messages;

use App\Core\Config\ConfigInterface;
use App\Core\Request\Request;

class MessagePutCookies implements MessagePutInterface {

    private Request $request;
    private ConfigInterface $config;

    public function __construct(ConfigInterface $config, Request $request) {
        $this->config = $config;
        $this->request = $request;
    }

    public function putMessages(array $messages): bool {
        $secure = $this->request->isSecure();
        if (!empty($messages)) {
            return setcookie('flash_msg', json_encode($messages), [
                'expires' => time() + 3600,
                'path' => '/',
                'secure' => $secure,
                'samesite' => $this->config->getSessionCookieSamesite(),
            ]);
        }
        $this->request->unsetCookie('flash_msg');
        return false;
    }

}
