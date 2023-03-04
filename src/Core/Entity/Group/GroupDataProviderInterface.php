<?php

declare(strict_types=1);

namespace App\Core\Entity\Group;

interface GroupDataProviderInterface {

    public function getEntity(): EntityGroupInterface;
}
