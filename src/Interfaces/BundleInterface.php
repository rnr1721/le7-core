<?php

declare(strict_types=1);

namespace Core\Interfaces;

/**
 * The BundleInterface represents the contract for a bundle (plugin).
 */
interface BundleInterface
{

    /**
     * Get bundle routes array
     * 
     * @return array
     */
    public function getRoutes(): array;

    /**
     * Get global menu config
     * Keys of this array will be added to global menu config
     * 
     * @return array Can be empty array if no menu items
     */
    public function getMenu(): array;

    /**
     * Get bundle directory
     * 
     * @return string|null
     */
    public function getPath(): ?string;

    /**
     * Returns the name of the bundle.
     *
     * @return string The bundle name.
     */
    public function getName(): string;

    /**
     * Returns the description of the bundle.
     *
     * @return string The bundle description.
     */
    public function getDescription(): string;

    /**
     * Returns a list of bundles that conflict with this bundle.
     *
     * This method should return an array of bundle names that conflict with the
     * current bundle. These are other bundles that should not be used together
     * with this bundle due to potential conflicts.
     *
     * @return array The list of conflicting bundle names.
     */
    public function getConflicts(): array;

    /**
     * Returns a list of bundles that are required by this bundle.
     *
     * This method should return an array of bundle names that are required by the
     * current bundle. These are other bundles that must be installed or loaded
     * in order for this bundle to work correctly.
     *
     * @return array The list of required bundle names.
     */
    public function getRequired(): array;

    /**
     * Returns a config for this bundle
     * 
     * @return array The list of parameters for bundle
     */
    public function getConfig(): array;
}
