<?php

namespace App\Core\Response\Output;

use App\Core\Response\Response;
use App\Core\Messages\MessageCollectionInterface;
use App\Core\Config\ConfigInterface;

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
