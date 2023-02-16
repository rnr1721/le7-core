<?php

declare(strict_types=1);

namespace le7\Core\Messages;

interface MessagePutInterface {

    /**
     * Put message to source
     * @param array $messages
     * @return bool
     */
    public function putMessages(array $messages): bool;
}
