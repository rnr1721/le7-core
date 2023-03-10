<?php

declare(strict_types=1);

namespace App\Core\View\Smarty;

use App\Core\View\ViewInterface;
use App\Core\View\ViewAdapterInterface;
use App\Core\Config\TopologyFsInterface;
use App\Core\Config\ConfigInterface;
use Smarty;

class SmartyAdapter implements ViewAdapterInterface
{

    private Smarty $smarty;

    public function __construct(Smarty $smarty, ConfigInterface $config, TopologyFsInterface $topology)
    {
        $smarty->setLeftDelimiter($config->getSmartyLeftDelimiter());
        $smarty->setRightDelimiter($config->getSmartyRightDelimiter());
        $this->smarty = $smarty;
        $templatePaths = array(
            $topology->getHtmlThemeAppPath(),
            $topology->getTemplatesSystemHtmlPath() . DIRECTORY_SEPARATOR
        );
        $cacheDir = $topology->getSmartyCachePath();
        $compiledDir = $topology->getSmartyCompiledPath();
        $pluginsSystem = $topology->getSmartyPluginsPathSystem();
        $pluginsUser = $topology->getSmartyPluginsPathUser();
        $smarty->setTemplateDir($templatePaths);
        $smarty->setCacheDir($cacheDir);
        $smarty->setCompileDir($compiledDir);
        $smarty->setCaching(0);
        $smarty->setErrorReporting(E_ALL);
        $smarty->setPluginsDir($pluginsSystem)->addPluginsDir($pluginsUser);
    }

    public function getView() : ViewInterface {
        return new SmartyView($this->smarty);
    }

}
