<?php

declare(strict_types=1);

namespace le7\Core\Controllers\Main;

use RedBeanPHP\OODBBean;
use le7\Core\User\UserInterface;
use le7\Core\Database\Database;
use le7\Core\Messages\MessageCollectionInterface;
use Psr\SimpleCache\CacheInterface;

use \ReflectionClass;

/**
 * @property Database $db 
 */
class Main
{

    private ?string $controllerId = null;
    protected UserInterface|OODBBean|null $user = null;
    public MessageCollectionInterface $messages;
    public CacheInterface $cache;

    public function trigger()
    {
        return array();
    }

    public function getControllerId(): string
    {
        if (empty($this->controllerId)) {
            return (new ReflectionClass($this))->getName();
        }
        return $this->controllerId;
    }

    public function setControllerId(string $controllerId)
    {
        $this->controllerId = $controllerId;
    }

}
