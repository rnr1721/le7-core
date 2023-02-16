<?php

namespace le7\Core\Config;

use le7\Core\Locales\Locales;

class PublicEnvFactory {

    private Locales $locales;
    private TopologyPublicInterface $topologyWeb;
    private UserConfigInterface $userConfig;

    public function __construct(Locales $locales, TopologyPublicInterface $topologyWeb, UserConfigInterface $userConfig) {
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
