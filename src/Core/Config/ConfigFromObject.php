<?php

declare(strict_types=1);

namespace App\Core\Config;

class ConfigFromObject implements ConfigInterface {

    /**
     * @var array
     */
    private array $configData;

    /**
     * @var array
     */
    private array $locales = array();

    public function __construct(string $basePath) {
        $configDev = $basePath . '/config/config_dev.ini';
        $config = $basePath . '/config/config.ini';
        if (file_exists($configDev)) {
            $this->configData = parse_ini_file($configDev, true);
        } else {
            $this->configData = parse_ini_file($config, true);
        }
        foreach ($this->configData['locales']['locales'] as $item => $value) {
            $localeData = explode('|', $value);
            $current = array(
                'name' => $localeData[0],
                'label' => $localeData[1]
            );
            $this->locales[$item] = $current;
        }
    }

    public function getPublicDir(): string {
        return $this->configData['general']['publicPath'];
    }

    public function getScriptVersion(): string {
        return $this->configData['general']['scriptVersion'];
    }

    /**
     * @return string
     */
    public function getProjectName(): string {
        return $this->configData['general']['projectName'];
    }

    /**
     * @return string
     */
    public function getDefaultController(): string {
        return $this->configData['general']['defaultController'];
    }

    /**
     * @return string
     */
    public function getDefaultAction(): string {
        return $this->configData['general']['defaultAction'];
    }

    /**
     * @return string
     */
    public function getDefaultLanguage(): string {
        return $this->configData['locales']['defaultLanguage'];
    }

    /**
     * @return array
     */
    public function getLocales(): array {
        return $this->locales;
    }

    /**
     *  Возвращает часовой пояс
     * @return string
     */
    public function getTimeZone(): string {
        return $this->configData['general']['timeZone'];
    }

    /**
     * @return string
     */
    public function getNotfoundController(): string {
        return $this->configData['general']['notfoundController'];
    }

    /**
     * @return string
     */
    public function getTheme(): string {
        return $this->configData['general']['theme'];
    }

    /**
     * @return bool
     */
    public function getErrorReporting(): bool {
        $value = $this->configData['general']['errorReporting'];
        if (empty($value)) {
            return false;
        }
        return true;
    }

    public function getIsProduction(): bool {
        $value = $this->configData['general']['isProduction'];
        if (empty($value)) {
            return false;
        }
        return true;
    }

    public function getSmartyLeftDelimiter(): string {
        return $this->configData['smarty']['leftDelimiter'];
    }

    public function getSmartyRightDelimiter(): string {
        return $this->configData['smarty']['rightDelimiter'];
    }

    public function getVarDir(): string {
        return $this->configData['general']['dirVar'];
    }

    public function getApiAllowedMethods(): string {
        return $this->configData['api']['allowedMethods'];
    }

    public function getApiAllowedHeaders(): string {
        return $this->configData['api']['allowedHeaders'];
    }

    public function getHeaderReferrerPolicy(): string {
        return $this->configData['headers']['referrerPolicy'];
    }

    public function getHeaderContentSecurityPolicy(): string {
        return $this->configData['headers']['contentSecurityPolicy'];
    }

    public function getHeaderStrictTransportSecurity(): string {
        return $this->configData['headers']['strictTransportSecurity'];
    }

    public function getHeaderXcontentTypeOptions(): string {
        return $this->configData['headers']['xContentTypeOptions'];
    }

    public function getHeaderXframeOptions(): string {
        return $this->configData['headers']['xFrameOptions'];
    }

    public function getHeaderXxssProtection(): string {
        return $this->configData['headers']['xXssProtection'];
    }

    public function getSessionCookieSamesite(): string {
        return $this->configData['sessions']['samesite'];
    }

    public function getDebugIps(): array {
        return explode(',', $this->configData['debug']['allowedIp']);
    }

    public function getDebugBarOn(): bool {
        $value = $this->configData['debug']['include'];
        if (empty($value)) {
            return false;
        }
        return true;
    }

    public function getCacheLifetime(): int|null {
        $cacheLifetime = $this->configData['cache']['cacheLifetime'];
        if ($cacheLifetime === "") {
            return null;
        } elseif ((int) $cacheLifetime === 0) {
            return 0;
        }
        return (int) $cacheLifetime;
    }

    public function getMemcacheConfig(): string {
        return $this->configData['cache']['memcache_connect'];
    }

    public function getMemcachedConfig(): string {
        return $this->configData['cache']['memcached_connect'];
    }

    public function getDefaultCacheMethod(): string {
        return $this->configData['cache']['default'];
    }

    public function exportConfig(): array {
        return $this->configData;
    }

    public function getFlashMessagesStorage(): string {
        return $this->configData['general']['flashMessageStorage'];
    }

    public function getUserManagementOn(): bool {
        $value = $this->configData['users']['userManagement'];
        if (empty($value)) {
            return false;
        }
        return true;
    }
    
    public function getUserLoginVerification(): bool {
        $value = $this->configData['users']['userLoginVerificationOn'];
        if (empty($value)) {
            return false;
        }
        return true;
    }

    public function getUserRegisterVerification(): bool {
        $value = $this->configData['users']['userRegisterVerificationOn'];
        if (empty($value)) {
            return false;
        }
        return true;
    }
    
    public function getUserIdentity():string{
        return $this->configData['users']['userIdentity'];
    }
    
    public function getUserLoginFields(): string{
        return $this->configData['users']['userLoginFields'];
    }

    public function getEmailConfig(): array {
        return $this->configData['email']['config'];
    }

    public function getNotificationClasses():array {
        return $this->configData['notification']['classes'];
    }
    
    public function getNotificationCases():string {
        return $this->configData['notification']['cases'];
    }

}
