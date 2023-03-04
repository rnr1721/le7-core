<?php

namespace App\Core\Entity\Single;

interface EntitySingleInterface {

    public function getData(): array|null;

    public function getRules(): array;

    public function getErrors(): array;
}
