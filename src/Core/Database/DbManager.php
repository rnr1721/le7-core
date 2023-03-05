<?php

declare(strict_types=1);

namespace App\Core\Database;

use App\Core\Entity\DataProviderFactory;
use App\Core\Entity\ModelSetup;
use App\Core\Config\DbConfig;
use App\Core\Config\DbConfigInterface;
use App\Core\Config\TopologyFsInterface;
use App\Core\Config\ConfigInterface;

class DbManager
{

    private DataProviderFactory $dataProviderFactory;
    private ModelSetup $modelSetup;
    private array $allowedDrivers = array(
        'mysql',
        'pgsql'
    );
    private DbConnInterface $dbConnection;
    private DbConfigInterface $dbConfig;
    private Db $db;
    private TopologyFsInterface $topologyFs;
    private ConfigInterface $config;

    public function __construct(ConfigInterface $config, TopologyFsInterface $topologyFs, ModelSetup $modelSetup)
    {
        $this->config = $config;
        $this->topologyFs = $topologyFs;
        $this->modelSetup = $modelSetup;
    }

    /**
     * Return database object
     * On first call it will try to connect DB
     * It simple decorator for Redbean with static methods
     * @return Db
     */
    public function getDb(): Db
    {
        if (empty($this->db)) {
            $this->modelSetup->prepareModels();
            $this->getDbConn();
            $this->db = new Db();
            $this->dbConnection->connect();
        }
        return $this->db;
    }

    /**
     * Get database connection
     * @return DbConnInterface
     * @throws \Exception
     */
    public function getDbConn(): DbConnInterface
    {
        if (empty($this->dbConnection)) {
            $this->getDbConfig();
            $driver = $this->dbConfig->getDbDriver();
            if (!in_array($driver, $this->allowedDrivers)) {
                throw new \Exception("DatabaseFactory::getDbConn() Please select right database driver in config");
            }
            $dbc = match ($driver) {
                'pgsql' => new DbPgSql(),
                'mysql' => new DbMySql(),
            };
            $this->dbConnection = new DbConn($dbc, $this->dbConfig, $this->config);
        }
        return $this->dbConnection;
    }

    /**
     * Get database configuration
     * @return DbConfigInterface
     */
    public function getDbConfig(): DbConfigInterface
    {
        if (empty($this->dbConfig)) {
            $this->dbConfig = new DbConfig($this->topologyFs);
        }
        return $this->dbConfig;
    }

    public function getDataProviderFactory(): DataProviderFactory
    {
        if (empty($this->dataProviderFactory)) {
            $this->getDbConn();
            $this->dataProviderFactory = new DataProviderFactory($this->dbConnection);
        }
        return $this->dataProviderFactory;
    }

}
