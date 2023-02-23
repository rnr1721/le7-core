<?php

declare(strict_types=1);

namespace le7\Core\User\Acl;

use Exception;

class AclCore implements AclCoreInterface {

    private array $resources = array();
    private array $roles = array();
    private array $permissions = array();
    private array $capabilities = array();

    public function setResource(string $resourceName): self {
        $this->resources[] = $resourceName;
        return $this;
    }

    public function setPermission(string $permissionName): self {
        $this->permissions[] = $permissionName;
        return $this;
    }

    public function setRole(string $roleName): self {
        $this->roles[] = $roleName;
        return $this;
    }

    private function resourceExists(string $resourceName): bool {
        if (in_array($resourceName, $this->resources)) {
            return true;
        }
        return false;
    }

    private function roleExists(string $roleName): bool {
        if (in_array($roleName, $this->roles)) {
            return true;
        }
        return false;
    }

    private function permissionExists(string $permissionName): bool {
        if (in_array($permissionName, $this->permissions)) {
            return true;
        }
        return false;
    }

    private function isAllExists(string $role, string $resource, string $permission): bool {
        if (!$this->roleExists($role)) {
            throw new Exception(_('Fatal error: role not exists:') . $role);
        }
        if (!$this->permissionExists($permission)) {
            throw new Exception(_('Fatal error: permission not exists:') . $permission);
        }
        if (!$this->resourceExists($resource)) {
            throw new Exception(_('Fatal error: resource not exists:') . $resource);
        }
        return true;
    }

    public function isCan(string $role, string $permission, string $resource): bool {
        if ($this->isAllExists($role, $resource, $permission)) {
            $needle = $role . ':' . $resource . ':' . $permission;
            if (in_array($needle, $this->capabilities)) {
                return true;
            }
        }
        return false;
    }

    public function can(string $role, string $permission, string $resource): bool {
        $r = explode(',', $permission);
        if (count($r) === 1) {
            if ($this->isAllExists($role, $resource, $permission)) {
                $needle = $role . ':' . $resource . ':' . $permission;
                $this->capabilities[] = $needle;
            }
        } else {
            foreach ($r as $current_permission) {
                if ($this->isAllExists($role, $resource, $current_permission)) {
                    $needle = $role . ':' . $resource . ':' . $current_permission;
                    $this->capabilities[] = $needle;
                }
            }
        }
    }

}
