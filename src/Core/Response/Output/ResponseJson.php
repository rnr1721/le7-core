<?php

namespace le7\Core\Response\Output;

class ResponseJson extends ResponseOutput {

    public function __invoke(array $data, int $code = 500, int $encode_option = 0): void {
        $this->emit($data, $code, $encode_option);
    }

    /**
     * Emit JSON data from array with any code
     * In this case - with messages, microtime, response code field
     * and "success" key equal false
     * and Content-Type = "application/json"
     * @param int $code Server response code
     * @param array $data Server response code
     * @param int $eo Encode option for json_encode
     * @return void
     */
    public function emitError(int $code = 200, array $data = array(), int $eo = 0): void {
        $data['success'] = false;
        $this->emit($data, $code, $eo);
    }

    /**
     * Emit JSON data from array with any code
     * In this case - with messages, microtime, response code field
     * and "success" key equal false
     * and Content-Type = "application/json"
     * @param int $code Server response code
     * @param array $data Array with output data
     * @param int $eo Encode option for json_encode
     * @return void
     */
    public function emitSuccess(int $code = 200, array $data = array(), int $eo = 0): void {
        $data['success'] = true;
        $this->emit($data, $code, $eo);
    }

    /**
     * Emit any array in JSON with microtime, messages, response code field
     * with Content-Type = "application/json"
     * @global string|float $start Microtime
     * @param array $data Array with output data
     * @param int $code Server response code
     * @param int $eo Encode option for json_encode
     * @return void
     */
    public function emit(array $data, int $code = 500, int $eo = 0): void {
        global $start;
        $data['info'] = $this->messages->getInfos(true);
        $data['errors'] = $this->messages->getErrors(true);
        $data['alerts'] = $this->messages->getAlerts(true);
        $data['warnings'] = $this->messages->getWarnings(true);
        $data['questions'] = $this->messages->getQuestions(true);
        $data['response_code'] = $code;
        $data['rtime'] = round(microtime(true) - $start, 4) . ' sec.';
        $this->emitClean($data, $code, $eo);
    }

    /**
     * Emit array in JSON as-is only json_encode
     * and with Content-type = "application/json"
     * @param array $data Array with output data
     * @param int $code Server response code
     * @param int $eo Encode option for json_encode
     * @return void
     */
    public function emitClean(array $data, int $code = 500, int $eo = 0): void {
        $output = json_encode($data, $eo);
        $this->emitRaw($output, $code);
    }

    /**
     * Emit already stringified JSON
     * with Content-type = "application/json"
     * @param string $data Array with output data
     * @param int $code Server response code
     */
    public function emitRaw(string $data, int $code) {
        $this->response->setBody($data);
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setResponseCode($code);
        $this->response->emit();
    }

    /**
     * Emit JSON for download as file
     * with Content-type = "application/json"
     * and other headers for download
     * @param array $data Array with output data
     * @param int $code Server response code
     * @param string $fileName Filename when download
     * @param int $eo Encode option for json_encode
     */
    public function emitForDownload(array $data, int $code = 201, string $fileName = "data.json", int $eo = 0) {
        $this->response->setHeader('Content-Description','File Transfer');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        $this->response->setHeader('Pragma','public');
        $this->response->setHeader('Cache-Control','must-revalidate');
        $this->emitClean($data, $code, $eo);
    }

}
