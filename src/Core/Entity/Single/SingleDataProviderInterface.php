<?php

namespace App\Core\Entity\Single;

use App\Core\Entity\Single\EntitySingleInterface;

interface SingleDataProviderInterface {

    public function getEntity(): EntitySingleInterface;

    public function getErrors(): array;
}
