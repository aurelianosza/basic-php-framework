<?php

namespace Core\Database\MysqlDriver;

class DatabaseJoin {

    private string $type = "INNER";
    private string $table;
    private string $onCondition;

    public static function make(
        string $type,
        string $table,
        string $onCondition
    ): DataBaseJoin
    {
        $databaseJoin = new DataBaseJoin();

        $databaseJoin->type = $type;
        $databaseJoin->table = $table;
        $databaseJoin->onCondition = $onCondition;

        return $databaseJoin;
    }

    public function mountCondition(): string
    {
        return " JOIN $this->table ON $this->onCondition \n";
    }

}
