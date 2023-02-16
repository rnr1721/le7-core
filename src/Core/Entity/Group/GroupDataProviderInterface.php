<?php

declare(strict_types=1);

namespace le7\Core\Entity\Group;

interface GroupDataProviderInterface {

    public function getEntity(): EntityGroupInterface;
}
