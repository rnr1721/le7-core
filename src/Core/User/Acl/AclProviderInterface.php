<?php

declare(strict_types=1);

namespace App\Core\User\Acl;

interface AclProviderInterface {

    /**
     * Check if some role can make something with some resource
     * @param string $role User role
     * @param string $permission Permissions,maybe comma separated
     * @param string $resource Resource name
     * @return bool
     */
    public function check(string $role, string $permission, string $resource): bool;
}
