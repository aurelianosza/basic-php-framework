<?php

namespace Core\Database;

use Core\Database\MysqlDriver\DatabaseFilter;
use Core\Database\MysqlDriver\DatabaseJoin;

class BaseRepository {
    
    private DatabaseDriverInterface $connection;

    private array $columns = [];
    private array $conditions = [];
    private array $joins = [];
    private int $limit = 15;

    public function __construct(
        private string $table,
        private $modelFactory = null)
    {}

    private function generateConnection(): DatabaseDriverInterface
    {
        $databaseConnectionName = "default";

        $this->connection = DatabaseConnectionFactory::getDatabaseInstance($databaseConnectionName);

        return $this->connection;
    }

    public function fetch(): array
    {
        $columns = count($this->columns) == 0
            ? ["*"]
            : $this->columns;

        $rows = $this->generateConnection()
            ->select(
                $this->table,
                $columns,
                $this->conditions,
                $this->limit,
                $this->joins,);

        if (!$this->modelFactory) {
            return $rows;
        }

        return array_map(
            fn (array $row) => call_user_func($this->modelFactory, $row),
            $rows
        );
    }

    public function first(): ?Entity
    {
        $rows = $this->limit(1)
            ->fetch();

        return count($rows) == 0
            ? null
            : $rows[0];
    }

    public function insert(array $dataToInsert): Entity
    {
        $insertedRow = $this->generateConnection()
            ->insert($this->table, $dataToInsert);

        return call_user_func($this->modelFactory, $insertedRow);
    }

    public function update($conditions, $dataToUpdate): array
    {
        return $this->generateConnection()
            ->update($this->table, $conditions, $dataToUpdate);
    }

    public function delete(array $conditions = []): array
    {
        return $this->generateConnection()
            ->delete($this->table, $conditions);
    }

    public function select(string ...$columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function where(string $column, string $operator, ?string $value = null): self
    {
        $this->conditions[] = DatabaseFilter::make($column, $operator, $value);
        return $this;
    }

    public function whereIN(string $column, array $values): self
    {
        $this->conditions[] = DatabaseFilter::make($column, "IN", $values);

        return $this;    
    }

    public function join(string $table, string $condition): self
    {
        $this->joins[] = DatabaseJoin::make("INNER", $table, $condition);

        return $this;    
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }
}
