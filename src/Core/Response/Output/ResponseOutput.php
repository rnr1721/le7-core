<?php

namespace le7\Core\Response\Output;

use le7\Core\Response\Response;
use le7\Core\Messages\MessageCollectionInterface;
use le7\Core\Config\ConfigInterface;

class ResponseOutput {
    
    protected Response $response;
    protected ConfigInterface $config;
    protected MessageCollectionInterface $messages;

    public function __construct(Response $response, ConfigInterface $config, MessageCollectionInterface $messages) {
        $this->response = $response;
        $this->config = $config;
        $this->messages = $messages;
    }
    
}
