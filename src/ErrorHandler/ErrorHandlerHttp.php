<?php

declare(strict_types=1);

namespace Core\ErrorHandler;

use Core\Interfaces\MessageCollection;
use Core\Interfaces\Config;
use Core\Interfaces\ErrorOutputResponse;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;

class ErrorHandlerHttp extends ErrorHandlerAbstract
{

    protected ErrorOutputResponse $output;

    public function __construct(
            Config $config,
            LoggerInterface $logger,
            MessageCollection $messages,
            ErrorOutputResponse $ouptut
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
