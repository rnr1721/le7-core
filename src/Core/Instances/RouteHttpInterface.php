<?php

declare(strict_types=1);

namespace le7\Core\Instances;

interface RouteHttpInterface extends RouteInterface {

    /**
     * Get base uri
     * @return string
     */
    public function getBase(): string;

    /**
     * Get current uri of route
     * @return string
     */
    public function getUri(): string;

    /**
     * Get request method of route
     * @return string
     */
    public function getMethod(): string;

    /**
     * Get proposed response code before process controller
     * @return int
     */
    public function getResponse(): int;
}
