<?php

namespace le7\Core\Config;

class CodePartsFactory {

    private ConfigInterface $config;
    private CodeParts $codeParts;
    private TopologyFsInterface $topologyFs;

    public function __construct(ConfigInterface $config, TopologyFsInterface $topologyFs, CodeParts $codeParts) {
        $this->topologyFs = $topologyFs;
        $this->codeParts = $codeParts;
        $this->config = $config;
        $folder = $topologyFs->getConfigUserPath() . DIRECTORY_SEPARATOR;
        $prod = $config->getIsProduction();
        $this->codeParts->register('top', $folder . 'stat_top.txt', $prod);
        $this->codeParts->register('middle', $folder . 'stat_middle.txt', $prod);
        $this->codeParts->register('bottom', $folder . 'stat_bottom.txt', $prod);
    }

    public function getStatTop(): string {
        return $this->codeParts->get('top', '');
    }

    public function getStatBottom(): string {
        return $this->codeParts->get('bottom', '');
    }

    public function getStatMiddle(): string {
        return $this->codeParts->get('middle', '');
    }

}
