<?php

declare(strict_types=1);

namespace Core\ErrorHandler;

use Core\Interfaces\MessageCollection;
use Core\Interfaces\Config;
use Core\Interfaces\ErrorOutputCli;
use Psr\Log\LoggerInterface;

class ErrorHandlerCli extends ErrorHandlerAbstract
{

    protected ErrorOutputCli $output;

    public function __construct(
            Config $config,
            LoggerInterface $logger,
            MessageCollection $messages,
            ErrorOutputCli $ouptut
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
