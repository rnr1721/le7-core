<?php

declare(strict_types=1);

namespace le7\Core\Messages;

interface MessageCollectionInterface {

    /**
     * Create message of something type
     * @param string $message Message text
     * @param string $status Message status
     * @param type $source Message source
     * @return self
     */
    public function newMsg(string $message, string $status = 'info', string $source = 'application'): self;

    /**
     * Get all messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getAll(bool $plain = false): array;
    
    /**
     * Get info messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getInfos(bool $plain = false): array;

    /**
     * Get warning messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getWarnings(bool $plain = false): array;
    
    /**
     * Get question messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getQuestions(bool $plain = false): array;
    
    /**
     * Get alert messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getAlerts(bool $plain = false): array;
    
    /**
     * Get error messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getErrors(bool $plain = false): array;
    
    /**
     * Emit alert message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollectionInterface
     */
    public function Alert(string $message, string $source="application") : self;
    
    /**
     * Emit warning message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollectionInterface
     */
    public function Warning(string $message, string $source="application") : self;
    
    /**
     * Emit question message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollectionInterface
     */
    public function Question(string $message, string $source="application") : self;
    
    /**
     * Emit info message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollectionInterface
     */
    public function info(string $message, string $source="application") : self;
    
    /**
     * Emit error message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollectionInterface
     */
    public function Error(string $message, string $source="application") : self;
    
    /**
     * Put messages to somewhere using interface
     * @param MessagePutInterface $putMethod Instance of MessagePut interface
     * @param string $type Type of messages or all
     * @return bool
     */
    public function putMessages(MessagePutInterface $putMethod, string $type = 'all'): bool;
    
    /**
     * Get messages somewhere
     * @param MessageGetInterface $getMethod Instance of messageGet interface
     * @param string $type Type of messages or all
     * @return array
     */
    public function getMessages(MessageGetInterface $getMethod, string $type = 'all'): array;
    
    /**
     * Some as getMessages, but messages load to current messages list
     * @param MessageGetInterface $getMethod Interface to load
     * @param string $type Message type
     * @return bool
     */
    public function loadMessages(MessageGetInterface $getMethod, string $type = 'all'): bool;
    
    /**
     * Clear all messages
     * @return void
     */
    public function clear() : void;
   
    /**
     * Messages empty? bool.
     * @return bool
     */
    public function isEmpty() : bool;
    
}
