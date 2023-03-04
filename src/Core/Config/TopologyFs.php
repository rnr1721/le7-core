<?php

declare(strict_types=1);

namespace App\Core\Config;

class TopologyFs implements TopologyFsInterface {

    private ConfigInterface $config;
    private string $basePath;
    private array $topology = array(
        'public' => '{dir_public}',
        'publicLibs' => '{dir_public}/libs',
        'htmlThemePublicPath' => '{dir_public}/themes/{dir_theme}/templates',
        'htmlThemeAppPath' => '{dir_public}/themes/{dir_theme}/templates',
        'templatesUserHtmlPath' => '{dir_public}/themes',
        'application' => '{path_base}',
        'core' => '{path_core}/Core',
        'configSystem' => '{path_core}/Core/Config',
        'configUser' => '{path_base}/config',
        'configSystemDi' => '{path_core}/Core/Config/Di',
        'configUserDi' => '{path_base}/Custom/Di',
        'configDiContainers' => '{path_base}/{dir_var}/containers',
        'configDiProxies' => '{path_base}/{dir_var}/containers/proxies',
        'locales' => '{path_base}/locales',
        'var' => '{path_base}/{dir_var}',
        'tempDir' => '{path_base}/{dir_var}/temp',
        'phpSessionsPath' => '{path_base}/{dir_var}/sessions',
        'logFolder' => '{path_base}/{dir_var}/logs',
        'routes' => '{path_base}/{dir_var}/routes',
        'cacheObjects' => '{path_base}/{dir_var}/cache',
        'errorTemplateFolder' => '{path_core}/Core/ErrorHandling/Templates',
        'templatesSystemHtmlPath' => '{path_core}/Core/Templates',
        'customConfigPath' => '{path_base}/Custom',
        'smartyCompiledPath' => '{path_base}/{dir_var}/smarty_compiled',
        'smartyCachePath' => '{path_base}/{dir_var}/smarty',
        'smartyPluginsPathSystem' => '{path_core}/Core/View/Smarty/smarty_plugins',
        'smartyPluginsPathUser' => '{path_base}/Custom/smarty_plugins',
        'uploadsInternalDir' => '{path_base}/uploads',
        'uploadsExternalDir' => '{dir_public}/uploads',
        'codeTemplates' => '{path_core}/Core/CodeTemplates',
        'controllerWebDir' => '{path_base}/Controller/Web',
        'controllerApiDir' => '{path_base}/Controller/Api',
        'controllerCliDir' => '{path_base}/Controller/Cli',
        'widgetsTemplate' => '{path_base}/Custom/Widgets',
        'widgetsTemplateSystem' => '{path_core}/Core/View/Widget/Templates'
    );

    public function __construct(string $basePath, string $corePath, string $publicPath, ConfigInterface $config) {
        $this->config = $config;
        $this->basePath = $basePath;
        foreach ($this->topology as &$item) {
            $search = array('{path_core}', '{path_base}', '{dir_theme}', '{dir_var}', '{dir_public}');
            $replace = array($corePath, $basePath, $this->config->getTheme(), $this->config->getVarDir(), $publicPath);
            $item = str_replace($search, $replace, $item);
            if (!file_exists($item)) {
                mkdir($item, 0775, true);
            }
        }
    }

    public function getBasePath(): string {
        return $this->basePath;
    }

    public function getCorePath(): string {
        return $this->topology['core'];
    }

    public function getApplicationPath(): string {
        return $this->topology['application'];
    }

    public function getVarPath(): string {
        return $this->topology['var'];
    }

    public function getConfigSystemPath(): string {
        return $this->topology['configSystem'];
    }

    public function getConfigUserPath(): string {
        return $this->topology['configUser'];
    }

    public function getConfigSystemDiPath(): string {
        return $this->topology['configSystemDi'];
    }

    public function getConfigUserDiPath(): string {
        return $this->topology['configUserDi'];
    }

    public function getPublicPath(): string {
        return $this->topology['public'];
    }

    public function getLocalesPath(): string {
        return $this->topology['locales'];
    }

    public function getPhpSessionsPath(): string {
        return $this->topology['phpSessionsPath'];
    }

    public function getLogFolder(): string {
        return $this->topology['logFolder'];
    }

    public function getConfigDiContainers(): string {
        return $this->topology['configDiContainers'];
    }

    public function getConfigDiProxies(): string {
        return $this->topology['configDiProxies'];
    }

    public function getRoutesCachePath(): string {
        return $this->topology['routes'];
    }

    public function getObjectCachePath(): string {
        return $this->topology['cacheObjects'];
    }

    public function getErrorTemplateFolder(): string {
        return $this->topology['errorTemplateFolder'];
    }

    public function getTemplatesSystemHtmlPath(): string {
        return $this->topology['templatesSystemHtmlPath'];
    }

    public function getTemplatesUserHtmlPath(): string {
        return $this->topology['templatesUserHtmlPath'];
    }

    public function getCustomConfigPath(): string {
        return $this->topology['customConfigPath'];
    }

    public function getSmartyCompiledPath(): string {
        return $this->topology['smartyCompiledPath'];
    }

    public function getSmartyCachePath(): string {
        return $this->topology['smartyCachePath'];
    }

    public function getSmartyPluginsPathSystem(): string {
        return $this->topology['smartyPluginsPathSystem'];
    }

    public function getSmartyPluginsPathUser(): string {
        return $this->topology['smartyPluginsPathUser'];
    }

    public function getHtmlThemePublicPath(): string {
        return $this->topology['htmlThemePublicPath'];
    }

    public function getHtmlThemeAppPath(): string {
        return $this->topology['htmlThemeAppPath'];
    }

    public function getUploadsInternalDir(): string {
        return $this->topology['uploadsInternalDir'];
    }

    public function getUploadsExternalDir(): string {
        return $this->topology['uploadsExternalDir'];
    }

    public function getCodeTemplatesDir(): string {
        return $this->topology['codeTemplates'];
    }

    public function getControllerWebDir(): string {
        return $this->topology['controllerWebDir'];
    }

    public function getControllerApiDir(): string {
        return $this->topology['controllerApiDir'];
    }

    public function getControllerCliDir(): string {
        return $this->topology['controllerCliDir'];
    }

    public function getTempDir(): string {
        return $this->topology['tempDir'];
    }

    public function getPublicLibsDir(): string {
        return $this->topology['publicLibs'];
    }
    
    public function getWidgetTemplateDir() : string {
        return $this->topology['widgetsTemplate'];
    }
    
    public function getWidgetTemplateSystemDir() : string {
        return $this->topology['widgetsTemplateSystem'];
    }
    
}
