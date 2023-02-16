<?php

declare(strict_types=1);

namespace le7\Core\Database;

use le7\Core\Config\ConfigInterface;
use le7\Core\Config\DbConfigInterface;
use RedBeanPHP\R;
use RedBeanPHP\BeanHelper;
use RedBeanPHP\RedException;

class DatabaseConnection {

    private bool $connected = false;
    private DbConfigInterface $c;
    private DbInterface $db;
    private ConfigInterface $config;

    /**
     * DatabaseConnection constructor.
     * @param DbInterface $db
     * @param ConfigInterface $config
     */
    public function __construct(DbInterface $db, DbConfigInterface $dbConfig, ConfigInterface $config) {
        $this->db = $db;
        $this->c = $dbConfig;
        $this->config = $config;
    }

    public function isConnected(): bool {
        return $this->connected;
    }

    /**
     *
     */
    public function connect(): void {
        if (!$this->connected) {

            $this->db->connect($this->c->getDbHost(), $this->c->getDbName(), $this->c->getDbUser(), $this->c->getDbPass());

            $connected = R::testConnection();

            if (!$connected) {
                throw new \Exception("DatabaseConnection::connect() Error while connect to database");
            }

            // If not is production, turn on db debug
            if (!$this->config->getIsProduction()) {
                R::debug(true, 1);
            }
            
            $this->connected = $connected;
        }
    }

    /**
     * @param string $key
     * @param string $dsn
     * @param string|null $user
     * @param string|null $pass
     * @param bool $frozen
     * @param bool $partialBeans
     * @param array $options
     * @param BeanHelper|null $beanHelper
     * @throws RedException
     */
    public function addDatabase(string $key, string $dsn, string $user = NULL, null|string $pass = NULL, bool $frozen = FALSE, bool $partialBeans = FALSE, array $options = array(), BeanHelper $beanHelper = NULL): void {
        R::addDatabase($key, $dsn, $user, $pass, $frozen, $partialBeans, $options, $beanHelper);
    }

    /**
     * @param string $key
     * @param bool $force
     * @return bool
     * @throws RedException
     */
    public function selectDatabase(string $key, bool $force = FALSE): bool {
        return R::selectDatabase($key, $force);
    }

    public function getPDO() {
        return R::getDatabaseAdapter()->getDatabase()->getPDO();
    }

}
