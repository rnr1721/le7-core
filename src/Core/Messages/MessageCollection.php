<?php

declare(strict_types=1);

namespace App\Core\Messages;

use Exception;

class MessageCollection implements MessageCollectionInterface {

    private array $msgTypes = array(
        'info',
        'error',
        'question',
        'alert',
        'warning'
    );
    private array $msgSources = array(
        'core',
        'application',
        'user',
        'event',
        'system'
    );
    private array $messages;

    public function __construct(array $messages = []) {
        $this->messages = $messages;
    }

    /**
     * Create message of something type
     * @param string $message Message text
     * @param string $status Message status
     * @param type $source Message source
     * @return self
     */
    public function newMsg(string $message, string $status = 'info', $source = 'application'): self {
        $this->checkSource($source);
        $this->checkType($status);
        $this->messages[] = array(
            'type' => $status,
            'message' => $message,
            'source' => $source
        );
        return $this;
    }

    /**
     * Get all messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getAll(bool $plain = false): array {
        if ($plain) {
            $result = array();
            foreach ($this->messages as $message) {
                $result[] = $message['message'];
            }
            return $result;
        }
        return $this->messages;
    }

    /**
     * Get info messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getInfos(bool $plain = false): array {
        return $this->getSpecial('info', $plain);
    }

    /**
     * Get warning messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getWarnings(bool $plain = false): array {
        return $this->getSpecial('warning', $plain);
    }

    /**
     * Get question messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getQuestions(bool $plain = false): array {
        return $this->getSpecial('question', $plain);
    }

    /**
     * Get alert messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getAlerts(bool $plain = false): array {
        return $this->getSpecial('alert', $plain);
    }

    /**
     * Get error messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getErrors(bool $plain = false): array {
        return $this->getSpecial('error', $plain);
    }

    /**
     * Get messages by type as array - full or plain
     * @param string $type Message type
     * @param bool $plain Full or plain array
     * @return array
     */
    private function getSpecial(string $type, bool $plain = false): array {
        $result = array();
        $this->checkType($type);
        foreach ($this->messages as $message) {
            if ($message['type'] === $type) {
                if ($plain) {
                    $result[] = $message['message'];
                } else {
                    $result[] = $message;
                }
            }
        }
        return $result;
    }

    /**
     * Check if message type is correct
     * @param string $type Message type
     * @return void
     */
    private function checkType(string $type): void {
        if (!in_array($type, $this->msgTypes)) {
            trigger_error(_('Message type not correct:') . $type, E_USER_ERROR);
        }
    }

    /**
     * Checks if message source can be used
     * @param string $source Message source for grouping
     * @return void
     */
    private function checkSource(string $source): void {
        if (!in_array($source, $this->msgSources)) {
            trigger_error(_('Message source not correct:') . $source, E_USER_ERROR);
        }
    }

    /**
     * Emit alert message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollectionInterface
     */
    public function Alert(string $message, string $source = "application"): MessageCollectionInterface {
        $this->newMsg($message, 'alert', $source);
        return $this;
    }

    /**
     * Emit error message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollectionInterface
     */
    public function Error(string $message, string $source = "application"): MessageCollectionInterface {
        $this->newMsg($message, 'error', $source);
        return $this;
    }

    /**
     * Emit question message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollectionInterface
     */
    public function Question(string $message, string $source = "application"): MessageCollectionInterface {
        $this->newMsg($message, 'question', $source);
        return $this;
    }

    /**
     * Emit warning message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollectionInterface
     */
    public function Warning(string $message, string $source = "application"): MessageCollectionInterface {
        $this->newMsg($message, 'warning', $source);
        return $this;
    }

    /**
     * Emit info message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollectionInterface
     */
    public function info(string $message, string $source = "application"): MessageCollectionInterface {
        $this->newMsg($message, 'info', $source);
        return $this;
    }

    /**
     * Put messages to somewhere using interface
     * @param MessagePutInterface $putMethod
     * @param string $type
     * @return void
     */
    public function putMessages(MessagePutInterface $putMethod, string $type = 'all'): bool {
        if ($type === 'all') {
            $messages = $this->messages;
        } else {
            $messages = $this->getSpecial($type);
        }
        return $putMethod->putMessages($messages);
    }

    /**
     * Get messages from provider (MessageGetInterface)
     * @param MessageGetInterface $getMethod
     * @param string $type
     * @return array
     * @throws Exception
     */
    public function getMessages(MessageGetInterface $getMethod, string $type = 'all'): array {
        $messages = $getMethod->getMessages();
        if (!is_array($messages)) {
            throw new Exception("MessageCollection::getMessages() broken messages. Must be array");
        }
        if ($type === 'all') {
            return $messages;
        }
        $result = [];
        foreach ($messages as $message) {
            if ($message['type'] === $type) {
                $result[] = $message;
            }
        }
        return $result;
    }

    /**
     * Load messages to current
     * @param MessageGetInterface $getMethod
     * @param string $type
     * @return bool
     */
    public function loadMessages(MessageGetInterface $getMethod, string $type = 'all'): bool {
        $messages = $this->getMessages($getMethod, $type);
        if (empty($messages)) {
            return false;
        }
        $this->messages = array_merge($this->messages, $messages);
        return true;
    }

    /**
     * Clear all messages
     */
    public function clear(): void {
        $this->messages = array();
    }

    /**
     * Is messages empty?
     * @return bool
     */
    public function isEmpty(): bool {
        if (count($this->messages) === 0) {
            return true;
        }
        return false;
    }

}
