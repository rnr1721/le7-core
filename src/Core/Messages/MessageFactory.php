<?php

declare(strict_types=1);

namespace le7\Core\Messages;

use le7\Core\Config\ConfigInterface;
use le7\Core\Request\Request;

class MessageFactory {

    private string $currentStorage;
    private ConfigInterface $config;
    private Request $request;

    public function __construct(ConfigInterface $config, Request $request) {
        $this->config = $config;
        $this->request = $request;
        $this->currentStorage = $config->getFlashMessagesStorage();
    }

    public function newInstance(array $messages = array()): MessageCollectionInterface {
        return new MessageCollection($messages);
    }

    public function getGetStorage(): MessageGetInterface {
        return match ($this->currentStorage) {
            'session' => new MessageGetSession(),
            'cookies' => new MessageGetCookies($this->request),
        };
    }

    public function getPutStorage(): MessagePutInterface {
        return match ($this->currentStorage) {
            'session' => new MessagePutSession(),
            'cookies' => new MessagePutCookies($this->config, $this->request)
        };
    }

}
