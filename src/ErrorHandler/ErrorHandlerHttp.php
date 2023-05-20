<?php

declare(strict_types=1);

namespace Core\ErrorHandler;

use Core\Interfaces\MessageCollectionInterface;
use Core\Interfaces\ConfigInterface;
use Core\Interfaces\ErrorOutputResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;

class ErrorHandlerHttp extends ErrorHandlerAbstract
{

    protected ErrorOutputResponseInterface $output;

    public function __construct(
            ConfigInterface $config,
            LoggerInterface $logger,
            MessageCollectionInterface $messages,
            ErrorOutputResponseInterface $ouptut
    )
    {
        parent::__construct($config, $logger, $messages);
        $this->output = $ouptut;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->output->get($this->exception, $this->errors);
    }

}
