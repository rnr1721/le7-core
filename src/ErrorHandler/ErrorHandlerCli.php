<?php

declare(strict_types=1);

namespace Core\ErrorHandler;

use Core\Interfaces\MessageCollectionInterface;
use Core\Interfaces\ConfigInterface;
use Core\Interfaces\ErrorOutputCliInterface;
use Psr\Log\LoggerInterface;

class ErrorHandlerCli extends ErrorHandlerAbstract
{

    protected ErrorOutputCliInterface $output;

    public function __construct(
            ConfigInterface $config,
            LoggerInterface $logger,
            MessageCollectionInterface $messages,
            ErrorOutputCliInterface $ouptut
    )
    {
        parent::__construct($config, $logger, $messages);
        $this->output = $ouptut;
    }

    public function getResponse(): void
    {
        $this->output->show($this->exception, $this->errors);
    }

}
