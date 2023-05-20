<?php

declare(strict_types=1);

namespace Core\Response;

use Core\Interfaces\MessageCollectionInterface;
use Psr\Http\Message\ResponseInterface;

class JsonResponse
{

    private ResponseInterface $response;
    private MessageCollectionInterface $messages;

    /**
     * JsonResponse constructor.
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

    public function __invoke(array $data, int $code = 500, int $encode_option = 0): ResponseInterface
    {
        return $this->emit($data, $code, $encode_option);
    }

    /**
     * Emit JSON data from array with any code
     * In this case - with messages, microtime, response code field
     * and "success" key equal false
     * and Content-Type = "application/json"
     * @param int $code Server response code
     * @param array $data Server response code
     * @param int $eo Encode option for json_encode
     * @return ResponseInterface
     */
    public function emitError(int $code = 200, array $data = array(), int $eo = 0): ResponseInterface
    {
        $data['success'] = false;
        return $this->emit($data, $code, $eo);
    }

    /**
     * Emit JSON data from array with any code
     * In this case - with messages, microtime, response code field
     * and "success" key equal false
     * and Content-Type = "application/json"
     * @param int $code Server response code
     * @param array $data Array with output data
     * @param int $eo Encode option for json_encode
     * @return ResponseInterface
     */
    public function emitSuccess(int $code = 200, array $data = array(), int $eo = 0): ResponseInterface
    {
        $data['success'] = true;
        return $this->emit($data, $code, $eo);
    }

    /**
     * Emit any array in JSON with microtime, messages, response code field
     * with Content-Type = "application/json"
     * @global string|float $start Microtime
     * @param array $data Array with output data
     * @param int $code Server response code
     * @param int $eo Encode option for json_encode
     * @return ResponseInterface
     */
    public function emit(array $data, int $code = 500, int $eo = 0): ResponseInterface
    {
        global $start;
        $data['info'] = $this->messages->getInfos(true);
        $data['errors'] = $this->messages->getErrors(true);
        $data['alerts'] = $this->messages->getAlerts(true);
        $data['warnings'] = $this->messages->getWarnings(true);
        $data['questions'] = $this->messages->getQuestions(true);
        $data['response_code'] = $code;
        if ($start) {
            $data['rtime'] = round(microtime(true) - floatval($start), 4) . ' sec.';
        }
        return $this->emitClean($data, $code, $eo);
    }

    /**
     * Emit array in JSON as-is only json_encode
     * and with Content-type = "application/json"
     * @param array $data Array with output data
     * @param int $code Server response code
     * @param int $eo Encode option for json_encode
     * @return ResponseInterface
     */
    public function emitClean(array $data, int $code = 500, int $eo = 0): ResponseInterface
    {
        $output = json_encode($data, $eo);
        return $this->emitRaw($output, $code);
    }

    /**
     * Emit already stringified JSON
     * with Content-type = "application/json"
     * @param string $data Array with output data
     * @param int $code Server response code
     * @return ResponseInterface
     */
    public function emitRaw(string $data, int $code): ResponseInterface
    {
        $this->response->getBody()->write($data);
        return $this->response
                        ->withStatus($code)
                        ->withHeader('Content-Type', 'application/json');
    }

    /**
     * Emit JSON for download as file
     * with Content-type = "application/json"
     * and other headers for download
     * @param array $data Array with output data
     * @param int $code Server response code
     * @param string $fileName Filename when download
     * @param int $eo Encode option for json_encode
     * @return ResponseInterface
     */
    public function emitForDownload(array $data, int $code = 201, string $fileName = "data.json", int $eo = 0): ResponseInterface
    {
        $headers = [
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'public',
            'Cache-Control' => 'must-revalidate'
        ];
        foreach ($headers as $hk => $hv) {
            $this->response = $this->response->withHeader($hk, $hv);
        }
        return $this->emitClean($data, $code, $eo);
    }

}
