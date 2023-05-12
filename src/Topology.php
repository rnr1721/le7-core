<?php

declare(strict_types=1);

namespace Core;

/**
 * Topology class. It generate system paths for config
 * And detect - production or non-production environment
 */
class Topology
{

    /**
     * If is production
     * @var bool
     */
    private bool $isProduction = false;

    /**
     * Topology array
     * @var array<array-key, string>
     */
    private array $topology = [
        'core' => '{core}',
        'base' => '{base}',
        'public' => '{public}',
        'config' => '{base}{ds}config',
        'locales' => '{base}{ds}App{ds}Locales',
        'var' => '{base}{ds}var',
        'logs' => '{base}{ds}var{ds}logs',
        'routes' => '{base}{ds}var{ds}routes',
        'cache' => '{base}{ds}var{ds}cache',
        'templates_cache' => '{base}{ds}var{ds}templates_cache',
        'templates_compiled' => '{base}{ds}var{ds}templates_compiled',
        'libs' => '{public}{ds}libs',
        'themes' => '{public}{ds}themes',
        'theme' => '{public}{ds}themes{ds}{current_theme}',
        'templates' => '{base}{ds}App{ds}View{ds}{current_theme}',
        'templates_base' => '{base}{ds}App{ds}View',
        'templates_errors' => '{core}{ds}ErrorHandler{ds}Templates'
    ];

    /**
     * This class require server public directory and project base directory
     * @param string $publicDir
     * @param string $baseDir
     */
    public function __construct(string $publicDir, string $baseDir)
    {
        $this->topology['core'] = realpath(dirname(__FILE__));
        $this->topology['public'] = $publicDir;
        $this->topology['base'] = $baseDir;
        $this->isProduction = file_exists($this->topology['base'] . DIRECTORY_SEPARATOR . 'production');
    }

    /**
     * Is production?
     * @return bool
     */
    public function getIsProduction(): bool
    {
        return $this->isProduction;
    }

    /**
     * This method exports current topology map of directories
     * @return array
     */
    public function export(): array
    {

        $ds = DIRECTORY_SEPARATOR;

        $search = [
            '{ds}',
            '{core}',
            '{base}',
            '{public}'
        ];

        /** @var string[] $replace */
        $replace = [
            $ds,
            $this->topology['core'],
            $this->topology['base'],
            $this->topology['public']
        ];

        foreach ($this->topology as &$item) {
            $item = str_replace($search, $replace, $item);
        }

        return $this->topology;
    }

}
