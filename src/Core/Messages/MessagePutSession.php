<?php

namespace App\Core\Messages;

class MessagePutSession implements MessagePutInterface {

    public function putMessages(array $messages): bool {
        if (session_status() === PHP_SESSION_ACTIVE) {
            if (!empty($messages)) {
                $_SESSION['flash_msg'] = $messages;
                return true;
            }
            unset($_SESSION['flash_msg']);
        }
        return false;
    }

}
