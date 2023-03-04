<?php

declare(strict_types=1);

namespace App\Core\User\Acl;

class AclProviderAbstract {
    
    private AclCore $aclCore;

    public function __construct(AclCoreInterface $aclCore) {
        $this->aclCore = $aclCore;
    }
    
    public function check(string $role, string $permission, string $resource):bool {
        return $this->aclCore->isCan($role, $permission, $resource);
    }
    
}
