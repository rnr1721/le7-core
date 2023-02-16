<?php

declare(strict_types=1);

namespace le7\Core\Messages;

interface MessageGetInterface {

    /**
     * Get messages from source as array
     * @return array
     */
    public function getMessages(): array;
}
