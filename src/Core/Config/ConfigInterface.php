<?php

namespace le7\Core\Config;

interface ConfigInterface {

    /**
     * Get the public server directory
     * @return string
     */
    public function getPublicDir(): string;

    /**
     * Get version of project
     * @return string
     */
    public function getScriptVersion(): string;

    /**
     * Get name of project
     * @return string
     */
    public function getProjectName(): string;

    /**
     * Get default controller that use if not specified
     * @return string
     */
    public function getDefaultController(): string;

    /**
     * Get default action that used if not specified
     * @return string
     */
    public function getDefaultAction(): string;

    /**
     * Get notfound controller for project
     * @return string
     */
    public function getNotfoundController(): string;

    /**
     * Get default language from config
     * @return string
     */
    public function getDefaultLanguage(): string;

    /**
     * Get locales array with available languages
     * @return array
     */
    public function getLocales(): array;

    /**
     * Get timezone
     * @return string
     */
    public function getTimezone(): string;

    /**
     * Get current theme
     * @return string
     */
    public function getTheme(): string;

    /**
     * Get if error reporting true or false
     * @return bool
     */
    public function getErrorReporting(): bool;

    /**
     * Production or development, true or false
     * @return bool
     */
    public function getIsProduction(): bool;

    /**
     * Left delimiter for smarty
     * @return string
     */
    public function getSmartyLeftDelimiter(): string;

    /**
     * Right delimiter for smarty
     * @return string
     */
    public function getSmartyRightDelimiter(): string;

    /**
     * Var directory name, be default "var"
     * @return string
     */
    public function getVarDir(): string;

    /**
     * Get allowed HTTP methods
     * @return string
     */
    public function getApiAllowedMethods(): string;

    /**
     * Get all allowed methods for API
     * @return string
     */
    public function getApiAllowedHeaders(): string;

    /**
     * Value of Referrer-Policy header
     * @return string
     */
    public function getHeaderReferrerPolicy(): string;

    /**
     * Header value for Content Security Policy
     * @return string
     */
    public function getHeaderContentSecurityPolicy(): string;

    /**
     * Header value for Strict-Transport-Security
     * @return string
     */
    public function getHeaderStrictTransportSecurity(): string;

    /**
     * Header value for X-Content-Type-Options
     * @return string
     */
    public function getHeaderXcontentTypeOptions(): string;

    /**
     * Header value for X-Frame-Options
     * @return string
     */
    public function getHeaderXframeOptions(): string;

    /**
     * Header value for X-Xss-Protection
     * @return string
     */
    public function getHeaderXxssProtection(): string;

    /**
     * Samesite value for PHP session cookie
     * @return string
     */
    public function getSessionCookieSamesite(): string;

    /**
     * Lost of IP addresses who can use Debug Bar
     * @return array
     */
    public function getDebugIps(): array;

    /**
     * On or off debug bar
     * @return bool
     */
    public function getDebugBarOn(): bool;

    /**
     * Get memcached config for connect ip:port
     * @return string
     */
    public function getMemcachedConfig(): string;

    /**
     * Get global cache lifetime from config
     * int value - cache lifetime in seconds
     * null value - disable cache
     * 0 - permanent cache without lifetime
     * @return int|null
     */
    public function getCacheLifetime(): int|null;

    /**
     * Get memcache config for connect ip:port
     * @return string
     */
    public function getMemcacheConfig(): string;

    /**
     * Get default caching method
     * file, memcache, memcached possible
     * @return string
     */
    public function getDefaultCacheMethod(): string;

    /**
     * Export config as array
     * @return array
     */
    public function exportConfig(): array;

    /**
     * Storage for flash messages
     * session or cookies
     * @return string
     */
    public function getFlashMessagesStorage(): string;

    /**
     * Is user management on?
     * @return bool
     */
    public function getUserManagementOn(): bool;

    public function getUserLoginVerification(): bool;
    
    public function getUserRegisterVerification(): bool;
    
    public function getUserIdentity(): string;
    
    public function getUserLoginFields(): string;
    
    /**
     * Get email configuration as array
     * @return array
     */
    public function getEmailConfig(): array;
    
    public function getNotificationClasses():array;
    
    public function getNotificationCases():string;
}
