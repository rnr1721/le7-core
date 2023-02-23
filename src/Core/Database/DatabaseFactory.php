<?php

declare(strict_types=1);

namespace le7\Core\Database;

use le7\Core\Entity\DataProviderFactory;
use le7\Core\Entity\ModelSetup;
use le7\Core\Config\DbConfig;
use le7\Core\Config\DbConfigInterface;
use le7\Core\Config\TopologyFsInterface;
use le7\Core\Config\ConfigInterface;

class DatabaseFactory {

    private DataProviderFactory $dataProviderFactory;
    private ModelSetup $modelSetup;
    private array $allowedDrivers = array(
        'mysql',
        'pgsql'
    );
    private DatabaseConnectionInterface $dbConnection;
    private DbConfigInterface $dbConfig;
    private Database $db;
    private TopologyFsInterface $topologyFs;
    private ConfigInterface $config;

    public function __construct(ConfigInterface $config, TopologyFsInterface $topologyFs, ModelSetup $modelSetup) {
        $this->config = $config;
        $this->topologyFs = $topologyFs;
        $this->modelSetup = $modelSetup;
    }

    public function getDatabase(): Database {
        if (empty($this->db)) {
            $this->modelSetup->prepareModels();
            $this->getDatabaseConnection();
            $this->db = new Database();
            $this->dbConnection->connect();
        }
        return $this->db;
    }

    public function getDatabaseConnection(): DatabaseConnectionInterface {
        if (empty($this->dbConnection)) {
            $this->getDatabaseConfig();
            $driver = $this->dbConfig->getDbDriver();
            if (!in_array($driver, $this->allowedDrivers)) {
                throw new \Exception("DatabaseFactory::getDatabaseConnection() Please select right database driver in config");
            }
            $dbc = match ($driver) {
                'pgsql' => new DbPgSql(),
                'mysql' => new DbMySql(),
            };
            $this->dbConnection = new DatabaseConnection($dbc, $this->dbConfig, $this->config);
        }
        return $this->dbConnection;
    }

    public function getDatabaseConfig(): DbConfigInterface {
        if (empty($this->dbConfig)) {
            $this->dbConfig = new DbConfig($this->topologyFs);
        }
        return $this->dbConfig;
    }

    public function getDataProviderFactory() : DataProviderFactory {
        if (empty($this->dataProviderFactory)) {
            $this->getDatabaseConnection();
            $this->dataProviderFactory = new DataProviderFactory($this->dbConnection);
        }
        return $this->dataProviderFactory;
    }
    
}
