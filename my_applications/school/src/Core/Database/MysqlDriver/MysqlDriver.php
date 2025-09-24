<?php

namespace Core\Database\MysqlDriver;

use Core\Database\DatabaseDriverInterface;
use Error;
use mysqli;

class MysqlDriver implements DatabaseDriverInterface {
    
    private mysqli $connection;

    public function __construct(MysqlConnectionDataDto $connectionData)
    {
        $this->connection = new mysqli(
           $connectionData->host,
           $connectionData->username,
           $connectionData->password,
           $connectionData->database,
           $connectionData->port
        );
    }

    private function verifyConnection()
    {
        if ($this->connection->connect_error) {
            throw new Error("Database connection error!");
        }
    }

    private function execute(string $sqlCommand)
    {
        $this->connection
            ->query($sqlCommand);
    }

    private function executeWithParams(string $sqlCommand, array $params)
    {
        $this->verifyConnection();

        $query = $this->connection->prepare($sqlCommand);

        $query->execute(array_values($params));

        return $query;
    }

    public function select(
        string $table,
        array $columns,
        array $conditions,
        ?int $limit = 15,
        array $joins = []
    ): array
    {
        $rawQuery = "SELECT \n";
        $rawQuery .= implode(", ", $columns) . "\n";
        $rawQuery .= "FROM $table \n";

        foreach ($joins as $join) {
            $rawQuery .= $join->mountCondition();
        }

        $conditionsValues = [];
        if (count($conditions) > 0) {
            $rawQuery .= "WHERE \n";
            $rawQuery .= $this->createPreparedParamsToConditions($conditions) . "\n";

            $conditionsValues = array_reduce(
                $conditions,
                fn ($carry, $condition) => [...$carry, ...$condition->getValue()],
                $conditionsValues
            );
        }

        $rawQuery .= "LIMIT $limit";

        $fetchData = $this->executeWithParams($rawQuery, $conditionsValues);

        $resultDataBag = $fetchData->get_result();

        $this->close();

        return $resultDataBag->fetch_all(MYSQLI_ASSOC);
    }

    public function insert(string $table, array $dataToInsert)
    {
        $rawQuery = "INSERT into \n";
        $rawQuery .=  $table . "\n";
        $rawQuery .= "(" . implode(", ", array_keys($dataToInsert)) . ")\n";
        $rawQuery .= "VALUES(";
        $rawQuery .=  $this->createPreparedParams($dataToInsert);
        $rawQuery .= ")";

        $execution = $this->executeWithParams($rawQuery, $dataToInsert);

        $this->close();

        return [
            "id" => $execution->insert_id,
            ...$dataToInsert
        ];
    }

    public function update(string $table, array $conditions, array $dataToUpdate): array
    {
        $rawQuery = "UPDATE \n";
        $rawQuery .= $table . "\n";
        $rawQuery .= "SET \n";
        $rawQuery .= $this->createPreparedParamsToMapOnUpdate($dataToUpdate). "\n";
        
        $conditionsValues = [];
        if (count($conditions) > 0) {
            $rawQuery .= "WHERE \n";
            $rawQuery .= $this->createPreparedParamsToConditions($conditions) . "\n";

            $conditionsValues = array_reduce(
                $conditions,
                fn ($carry, $condition) => [...$carry, ...$condition->getValue()],
                $conditionsValues
            );
        }

        $dataUpdated =  (array)$this->executeWithParams($rawQuery, [
            ...array_values($dataToUpdate),
            ...$conditionsValues
        ]);

        $this->close();

        return $dataUpdated;
    }

    public function delete(string $table, array $conditions): array
    {
        $rawQuery = "DELETE FROM \n";
        $rawQuery .= "$table \n";

        $conditionsValues = [];
        if (count($conditions) > 0) {
            $rawQuery .= "WHERE \n";
            $rawQuery .= $this->createPreparedParamsToConditions($conditions) . "\n";

            $conditionsValues = array_reduce(
                $conditions,
                fn ($carry, $condition) => [...$carry, ...$condition->getValue()],
                $conditionsValues
            );
        }

        $dataDeleted = (array)$this->executeWithParams($rawQuery, [
            ...$conditionsValues
        ]);

        $this->close();

        return $dataDeleted;
    }

    private function createPreparedParams(array $data): string
    {
        return implode(
            ", ",
            array_map(
                fn($_item) => "?",
                range(1, count($data))
            )
        );
    }

    private function createPreparedParamsToMapOnUpdate(array $data): string
    {
        $params = array_map(
            fn(string $column) => "$column = ?",
            array_keys($data)
        );

        return implode(
            ", ",
            $params
        );
    }

    private function createPreparedParamsToConditions(array $conditions): string
    {
        $conditionsFilter = array_map(
            fn(DatabaseFilter $condition) => $condition->mountCondition(),
            $conditions
        );

        return implode(
            " AND \n",
            $conditionsFilter
        );
    }

    public function close(): bool
    {
        return $this->connection->close();    
    }

}
