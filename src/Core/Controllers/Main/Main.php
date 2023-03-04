<?php

declare(strict_types=1);

namespace App\Core\Controllers\Main;

use RedBeanPHP\OODBBean;
use App\Core\User\UserInterface;
use App\Core\Database\Db;
use App\Core\Messages\MessageCollectionInterface;
use Psr\SimpleCache\CacheInterface;

use \ReflectionClass;

/**
 * @property Db $db 
 */
class Main
{

    private ?string $controllerId = null;
    protected UserInterface|OODBBean|null $user = null;
    public MessageCollectionInterface $messages;
    public CacheInterface $cache;

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
