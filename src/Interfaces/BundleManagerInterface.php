<?php

declare(strict_types=1);

namespace Core\Interfaces;

/**
 * The BundleManagerInterface represents the contract for managing bundles.
 */
interface BundleManagerInterface
{

    /**
     * Initializes configuration of all added bundles.
     *
     * After calling this method, if you try to add more bundles, it will
     * throw an exception.
     *
     * @return void
     */
    public function configureAll(): void;

    /**
     * Run init method on all added bundles
     * 
     * @return void
     */
    public function initAll(): void;

    /**
     * Get list of working bundles
     * 
     * @return array List of working bundles
     */
    public function getList(): array;

    /**
     * Adds a bundle class.
     *
     * @param string $bundle The class name of the BundleInterface implementation.
     * @return self
     */
    public function addBundle(string $bundle): self;

    /**
     * Adds an array of bundles.
     *
     * @param string[] $bundles An array of class names representing
     * BundleInterface implementations.
     * @return self
     */
    public function addBundles(array $bundles): self;
}
