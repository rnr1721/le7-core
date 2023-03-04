<?php

use DI\ContainerBuilder;

/** @var App\Core\Config\TopologyFsInterface $topology */
global $topology;
/** @var App\Core\Config\ConfigInterface $config */
global $config;

$builder = new ContainerBuilder();

$builder->useAutowiring(true);
//$builder->useAnnotations(false);

$depFiles = array_merge(
    glob($topology->getConfigSystemDiPath().'/*Deps.php' ?: []),
    glob($topology->getConfigUserDiPath().'/*Deps.php' ?: [])
);

$assembledConfig = array_map(function (string $file){
    return require $file;
},$depFiles);

$configFromFiles = array_merge_recursive($assembledConfig);

if ($config->getIsProduction()) {
    $builder->enableCompilation($topology->getConfigDiContainers());
    $builder->writeProxiesToFile(true, $topology->getConfigDiProxies());
}

$builder->addDefinitions(...$configFromFiles);

try {
    return $builder->build();
} catch (Exception $e) {
    return null;
}
