<?php

namespace Core\Database\MysqlDriver;

class MysqlConnectionDataDto {

    public ?string $host;    
    public ?string $username;
    public ?string $password;
    public ?string $database;
    public ?int $port;

    public static function fromArray(array $mysqlData): MysqlConnectionDataDto
    {
        $mysqlConnectionDataDto = new MysqlConnectionDataDto();

        $mysqlConnectionDataDto->host = dot_get($mysqlData, "host");
        $mysqlConnectionDataDto->username = dot_get($mysqlData, "username");
        $mysqlConnectionDataDto->password = dot_get($mysqlData, "password");
        $mysqlConnectionDataDto->database = dot_get($mysqlData, "database");
        $mysqlConnectionDataDto->port = dot_get($mysqlData, "port");

        return $mysqlConnectionDataDto;
    }

    private function __construct()
    {}

}
