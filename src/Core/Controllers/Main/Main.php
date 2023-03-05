<?php

declare(strict_types=1);

namespace App\Core\Controllers\Main;

use RedBeanPHP\OODBBean;
use App\Core\User\UserInterface;
use App\Core\Messages\MessageCollectionInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Default controller for all controllers
 * All base controllers must extends from it
 */
class Main
{

    /**
     * Current user or null
     * @var UserInterface|OODBBean|null
     */
    protected UserInterface|OODBBean|null $user = null;
    
    /**
     * System messages - alerts, warnings, errors etc
     * @var MessageCollectionInterface
     */
    public MessageCollectionInterface $messages;
    
    /**
     * Cache
     * @var CacheInterface
     */
    public CacheInterface $cache;

    /**
     * Get unique id of controller
     * @return string
     */
    public function getControllerId(): string
    {
        return (md5(get_class($this)));
    }

}
