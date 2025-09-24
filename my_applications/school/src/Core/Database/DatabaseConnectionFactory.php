<?php

namespace Core\Database;

use Core\Database\MysqlDriver\MysqlConnectionDataDto;
use Core\Database\MysqlDriver\MysqlDriver;
use Error;

class DatabaseConnectionFactory {
    
    public static function getDatabaseInstance(string $connectionName): DatabaseDriverInterface
    {
        $connectionRawData = config("database.connections." . $connectionName, []);

        if (count($connectionRawData) == 0) {
            throw new Error("connection settings for $connectionName not found.");
        }

        $connectionData = MysqlConnectionDataDto::fromArray($connectionRawData);

        return new MysqlDriver($connectionData);
    }
}
