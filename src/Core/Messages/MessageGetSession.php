<?php

namespace le7\Core\Messages;

class MessageGetSession implements MessageGetInterface {

    public function getMessages(): array {
        if (session_status() === PHP_SESSION_ACTIVE) {
            if (!empty($_SESSION['flash_msg'])) {
                $result = $_SESSION['flash_msg'];
                unset($_SESSION['flash_msg']);
                return $result;
            }
        }
        return [];
    }

}
