<?php

declare(strict_types=1);

namespace App\Core\Entity\Rules;

interface RulesInterface {

    /**
     * Get entity rules and fields
     * @return array|null
     */
    public function getRules(): array;

    /**
     * Get name
     * @return string
     */
    public function getName(): string;
}
