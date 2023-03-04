<?php

declare(strict_types=1);

namespace App\Core\User\Acl;

interface AclCoreInterface {

    public function setResource(string $resourceName): self;

    public function setPermission(string $permissionName): self;

    public function setRole(string $roleName): self;

    public function isCan(string $role, string $permission, string $resource): bool;

    public function can(string $role, string $permission, string $resource): bool;
}
