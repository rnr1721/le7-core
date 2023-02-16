<?php

namespace le7\Core\Config;

interface TopologyFsInterface {

    /**
     * Get base path of project
     * ./
     * @return string
     */
    public function getBasePath(): string;

    /**
     * ./vendor/rnr1721/le7-core/src/Core
     * Get path of Core in vendor folder
     * @return string
     */
    public function getCorePath(): string;

    /**
     * Get path for Var folder in project folder
     * ./var by default
     * @return string
     */
    public function getVarPath(): string;

    /**
     * Get project path folder
     * ./ of project
     * @return string
     */
    public function getApplicationPath(): string;

    /**
     * Get config path in core
     * ./vendor/rnr1721/le7-core/src/Core/Config
     * @return string
     */
    public function getConfigSystemPath(): string;

    /**
     * Get config folder path of project folder
     * ./config
     * @return string
     */
    public function getConfigUserPath(): string;

    /**
     * Path for PHP-Di injection folder
     * ./vendor/rnr1721/le7-core/src/Core/Config/Di
     * @return string
     */
    public function getConfigSystemDiPath(): string;

    /**
     * Path for User Di folder
     * ./Custom/Di
     * @return string
     */
    public function getConfigUserDiPath(): string;

    /**
     * PHP-Di cache folder in production mode
     * ./var/containers
     * @return string
     */
    public function getConfigDiContainers(): string;

    /**
     * PHP-Di files in production mode
     * ./var/containers/proxies
     * @return string
     */
    public function getConfigDiProxies(): string;

    /**
     * Public dir of webserver
     * ./htdocs by default
     * @return string
     */
    public function getPublicPath(): string;

    /**
     * Locales path
     * ./locales
     * @return string
     */
    public function getLocalesPath(): string;

    /**
     * PHP sessions path
     * ./var/sessions by default
     * @return string
     */
    public function getPhpSessionsPath(): string;

    /**
     * Logs path
     * ./var/logs by default
     * @return string
     */
    public function getLogFolder(): string;

    /**
     * Routes cache
     * ./var/routes by default
     * @return string
     */
    public function getRoutesCachePath(): string;

    /**
     * Object cache folder
     * ./var/cache by default
     * @return string
     */
    public function getObjectCachePath(): string;

    /**
     * Folder of templates needed for error handling
     * ./vendor/rnr1721/le7-core/src/Core/ErrorHandling/Templates
     * @return string
     */
    public function getErrorTemplateFolder(): string;

    /**
     * Template for system pages
     * ./vendor/rnr1721/le7-core/src/Core/Templates
     * @return string
     */
    public function getTemplatesSystemHtmlPath(): string;

    /**
     * Templates for projects
     * ./htdocs/themes/{theme}/templates by default
     * @return string
     */
    public function getTemplatesUserHtmlPath(): string;

    /**
     * Custom config folder
     * ./Custom by default
     * @return string
     */
    public function getCustomConfigPath(): string;

    /**
     * Smarty compiled files
     * ./var/smarty_compiled by default
     * @return string
     */
    public function getSmartyCompiledPath(): string;

    /**
     * Smarty cache
     * ./var/smarty by default
     * @return string
     */
    public function getSmartyCachePath(): string;

    /**
     * System place for Smarty plugins
     * ./vendor/rnr1721/le7-core/src/Core/View/Smarty/smarty_plugins
     * @return string
     */
    public function getSmartyPluginsPathSystem(): string;

    /**
     * Smarty plugins in userspace for own plugins
     * ./Custom/smarty_plugins by default
     * @return string
     */
    public function getSmartyPluginsPathUser(): string;

    /**
     * Theme public path
     * ./htdocs/themes/{theme} by default
     * @return string
     */
    public function getHtmlThemePublicPath(): string;

    /**
     * Theme templates path
     * ./htdocs/themes/{theme}/templates
     * @return string
     */
    public function getHtmlThemeAppPath(): string;

    /**
     * Directory for store uploaded files in project directory
     * ./uploads by default
     * @return string
     */
    public function getUploadsInternalDir(): string;

    /**
     * Directory for store uploaded files in public directory
     * ./htdocs/uploads by default
     * @return string
     */
    public function getUploadsExternalDir(): string;

    /**
     * Code templates Core dir
     * ./vendor/rnr1721/le7-core/src/Core/CodeTemplates
     * @return string
     */
    public function getCodeTemplatesDir(): string;

    /**
     * Controllers for Web dir
     * ./Controller/Web
     * @return string
     */
    public function getControllerWebDir(): string;

    /**
     * Controllers for Api dir
     * ./Controller/Api
     * @return string
     */
    public function getControllerApiDir(): string;

    /**
     * Controllers for console dir
     * ./Controller/Cli
     * @return string
     */
    public function getControllerCliDir(): string;

    /**
     * Directory for temp files
     * ./vat/temp by default
     * @return string
     */
    public function getTempDir(): string;

    /**
     * Public libs dir for fontawesome, bootstrap etc
     * ./htdocs/libs by default
     * @return string
     */
    public function getPublicLibsDir(): string;
    
    /**
     * 
     * @return string
     */
    public function getWidgetTemplateDir() : string;
    
    /**
     * 
     * @return string
     */
    public function getWidgetTemplateSystemDir() : string;
}
