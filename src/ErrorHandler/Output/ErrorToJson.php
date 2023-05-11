<?php

declare(strict_types=1);

namespace Core\ErrorHandler\Output;

use Core\Interfaces\ErrorOutputResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use \Throwable;

class ErrorToJson implements ErrorOutputResponse
{

    protected ResponseFactoryInterface $factory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->factory = $responseFactory;
    }

    public function get(Throwable|null $exception, array $errors): ResponseInterface
    {
        if (ob_get_contents()) {
            ob_clean();
        }
        $result = array(
            'result' => null,
            'success' => false,
            'errors' => array()
        );
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $result['errors'][] = $error['errorType'] . ' > ' . $error['errorString'] . ' > ' . $error['errorFile'] . ' (' . $error['errorLine'] . ')';
            }
        }
        if (!empty($exception)) {
            $result['errors'][] = $exception->getMessage() . ' > ' . $exception->getFile() . ' (' . $exception->getLine() . ')';
            foreach ($exception->getTrace() as $traceItem) {
                $place = '';
                if (!empty($traceItem['class'])) {
                    $place .= ' > ' . $traceItem['class'];
                }
                if (!empty($traceItem['function'])) {
                    $place .= ' > ' . $traceItem['function'];
                }
                $result['errors'][] = $place . ' > ' . (isset($traceItem['file']) ? $traceItem['file'] : '') . ' > (' . (isset($traceItem['line']) ? $traceItem['line'] : '') . ')';
            }
        }
        $output = json_encode($result);
        $response = $this->factory->createResponse(503);
        $response->getBody()->write($output);
        $size = $response->getBody()->getSize();
        return $response
                        ->withHeader('Content-Type', 'application/json')
                        ->withHeader('Content-Length', (string) $size);
    }

}
