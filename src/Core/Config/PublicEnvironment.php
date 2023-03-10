<?php

declare(strict_types=1);

namespace App\Core\Config;

use App\Core\Config\UserConfigInterface;
use App\Core\Locales\LocalesInterface;
use App\Core\Config\TopologyPublicInterface;

class PublicEnvironment {

    private array $userVars = array();
    private PublicEnvInterface $publicEnv;
    private LocalesInterface $locales;
    private UserConfigInterface $uconfig;
    private TopologyPublicInterface $topologyWeb;

    public function __construct(LocalesInterface $locales, TopologyPublicInterface $topologyWeb, UserConfigInterface $userConfig, PublicEnvInterface $publicEnv) {
        $this->locales = $locales;
        $this->uconfig = $userConfig;
        $this->topologyWeb = $topologyWeb;
        $this->publicEnv = $publicEnv;
    }

    public function getEnvironment(): array {
        $result = array(
            'root' => $this->topologyWeb->getBaseUrl(),
            'js' => $this->topologyWeb->getJsUrl(),
            'css' => $this->topologyWeb->getCssUrl(),
            'libs' => $this->topologyWeb->getLibsUrl(),
            'language' => $this->locales->getCurrentLocaleShortname(),
            'locales' => $this->locales->getLocalesByShortname()
        );
        if (is_object($this->uconfig->environment)) {
            foreach ($this->uconfig->environment as $key => $value) {
                $result[$key] = $value;
            }
        }
        foreach ($this->userVars as $uKey => $uValue) {
            $result[$uKey] = $uValue;
        }
        return $result;
    }

    public function addOwn(string $key, string|array $value): self {
        if (is_array($value)) {
            $value = json_encode($value);
        }
        $this->userVars[$key] = $value;
        return $this;
    }

    public function export(): string {
        return $this->publicEnv->export($this->getEnvironment());
    }

}
