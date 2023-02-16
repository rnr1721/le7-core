<?php

declare(strict_types=1);

namespace le7\Core\Messages;

use le7\Core\Request\Request;

class MessageGetCookies implements MessageGetInterface {

    private Request $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function getMessages(): array {
        $result = $this->request->getCookieParam('flash_msg');
        if ($result) {
            $this->request->unsetCookie('flash_msg');
            if ($this->isJson($result)) {
                return json_decode($result, true);
            }
        }
        return [];
    }

    private function isJson(string $string): bool {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

}
