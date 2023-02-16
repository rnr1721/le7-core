<?php

declare(strict_types=1);

namespace le7\Core\Instances;

interface RouteInterface {

    /**
     * get type of route,- api,web or cli
     * @return string
     */
    public function getType(): string;

    /**
     * Get case of route
     * for example, "api/v1" or "admin"
     * @return string
     */
    public function getCase(): string;

    /**
     * Get controller shortname, for example - "mycontroller"
     * @return string
     */
    public function getController(): string;

    /**
     * Get action shortname, for example - "myaction"
     * @return string
     */
    public function getAction(): string;

    /**
     * Full name of controller class,
     * for example le7\Controller\Web\MycontrollerController 
     * @return string
     */
    public function getControllerClass(): string;

    /**
     * Full name of action method
     * for example indexAction or indexGetAction for Api or Ajax
     * @return string
     */
    public function getActionMethod(): string;

    /**
     * Get URL params as array or empty array
     * @return array
     */
    public function getParams(): array;

    /**
     * Return one URL parameter by name or default value
     * @param string $param Name of the parameter
     * @param string|int|bool|null $default Default value if parameter not exist
     * @return string|int|bool|null
     */
    public function getParam(string $param, string|int|bool|null $default): string|int|bool|null;

    /**
     * Get language of route by URL in web or Content-Language in
     * Ajax or Api case
     * @return string
     */
    public function getLanguage(): string;

    /**
     * Export route as key=>value array
     * @return array
     */
    public function exportArray(): array;

    /**
     * Export route as object
     * @return object
     */
    public function exportObject(): object;

    /**
     * Get not found route for current case
     * @return array
     */
    public function getNotFound(): array;
}
