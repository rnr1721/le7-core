<?php

declare(strict_types=1);

namespace Core\Interfaces;

interface RouteInterface
{

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
     * for example App\Controller\Web\MycontrollerController 
     * @return class-string
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
     * @return array<array-key, string>
     */
    public function getParams(): array;
    
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

}
