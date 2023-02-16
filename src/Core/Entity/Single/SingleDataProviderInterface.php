<?php

namespace le7\Core\Entity\Single;

use le7\Core\Entity\Single\EntitySingleInterface;

interface SingleDataProviderInterface {

    public function getEntity(): EntitySingleInterface;

    public function getErrors(): array;
}
