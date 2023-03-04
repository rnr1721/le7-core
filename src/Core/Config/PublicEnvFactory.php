<?php

namespace App\Core\Config;

use App\Core\Locales\LocalesInterface;

class PublicEnvFactory {

    private LocalesInterface $locales;
    private TopologyPublicInterface $topologyWeb;
    private UserConfigInterface $userConfig;

    public function __construct(LocalesInterface $locales, TopologyPublicInterface $topologyWeb, UserConfigInterface $userConfig) {
        $this->locales = $locales;
        $this->topologyWeb = $topologyWeb;
        $this->userConfig = $userConfig;
    }

    public function getEnvJson(): PublicEnvironment {
        $envJson = new PublicEnvJson();
        return $this->getEnv($envJson);
    }

    public function getEnvHtml(): PublicEnvironment {
        $envHtml = new PublicEnvHtml();
        return $this->getEnv($envHtml);
    }

    public function getEnv(PublicEnvInterface $method): PublicEnvironment {
        return new PublicEnvironment($this->locales, $this->topologyWeb, $this->userConfig, $method);
    }

}
