<?php

namespace Core\Response;

use Core\Interfaces\MessageCollectionInterface;
use Psr\Http\Message\ResponseInterface;

class JsonpResponse
{

    private ResponseInterface $response;
    private MessageCollectionInterface $messages;

    /**
     * JsonpResponse constructor.
     * @param ResponseInterface $response The PSR-7 HTTP response object
     * @param MessageCollectionInterface $messageCollection The message collection object
     */
    public function __construct(
            ResponseInterface $response,
            MessageCollectionInterface $messageCollection
    )
    {
        $this->response = $response;
        $this->messages = $messageCollection;
    }

    /**
     * Emit a successful JSONP response.
     * @param string $callbackName The JSONP callback function name
     * @param array $data The response data
     * @param int $code The HTTP response code
     * @param int $encodeOption The JSON encode option
     * @return ResponseInterface The modified response object
     */
    public function emitSuccess(string $callbackName, array $data = [], int $code = 200, int $encodeOption = 0): ResponseInterface
    {
        $data['success'] = true;
        return $this->emit($callbackName, $data, $code, $encodeOption);
    }

    /**
     * Emit an error JSONP response.
     * @param string $callbackName The JSONP callback function name
     * @param array $data The response data
     * @param int $code The HTTP response code
     * @param int $encodeOption The JSON encode option
     * @return ResponseInterface The modified response object
     */
    public function emitError(string $callbackName, array $data = [], int $code = 500, int $encodeOption = 0): ResponseInterface
    {
        $data['success'] = false;
        return $this->emit($callbackName, $data, $code, $encodeOption);
    }

    /**
     * Emit a JSONP response.
     * @param string $callbackName The JSONP callback function name
     * @param array $data The response data
     * @param int $code The HTTP response code
     * @param int $encodeOption The JSON encode option
     * @return ResponseInterface The modified response object
     */
    public function emit(string $callbackName, array $data = [], int $code = 200, int $encodeOption = 0): ResponseInterface
    {
        $data['info'] = $this->messages->getInfos(true);
        $data['errors'] = $this->messages->getErrors(true);
        $data['alerts'] = $this->messages->getAlerts(true);
        $data['warnings'] = $this->messages->getWarnings(true);
        $data['questions'] = $this->messages->getQuestions(true);
        $data['response_code'] = $code;

        $output = json_encode($data, $encodeOption);
        $jsonpOutput = $callbackName . '(' . $output . ');';

        $this->response->getBody()->write($jsonpOutput);
        return $this->response
                        ->withStatus($code)
                        ->withHeader('Content-Type', 'application/javascript');
    }

}
