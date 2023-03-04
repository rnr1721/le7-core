<?php

declare(strict_types=1);

namespace App\Core\Instances;

interface RouteHttpInterface extends RouteInterface
{

    /**
     * Get base uri
     * @return string
     */
    public function getBase(): string;

    /**
     * Get base root
     * @return string
     */
    public function getBaseRoot(): string;

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

    /**
     * Middleware that will applied for this route
     * @return array
     */
    public function getMiddleware(): array;

    /**
     * Array with classes, that will be injected as properties
     * to controller after created
     * @return array
     */
    public function getInject(): array;
}
