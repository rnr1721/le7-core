<?php

declare(strict_types=1);

namespace Core\Interfaces;

interface RouteHttp extends Route
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
     * Return one URL parameter by name or default value
     * @param int $param Name of the parameter
     * @param string|null $default Default value if parameter not exist
     * @return string|null
     */
    public function getParam(int $param, string|null $default = null): string|null;

    /**
     * is route secured by csrf protection?
     * To turn on csrf for route: #[Params(csrf:true)]
     * Turned on by default for POST, PUT, DELETE, PATCH requests
     * @return bool
     */
    public function getCsrf(): bool;
}
