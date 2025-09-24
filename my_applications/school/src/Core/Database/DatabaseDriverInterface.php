<?php

namespace Core\Database;

interface DatabaseDriverInterface {
    public function select(string $table, array $columns, array $conditions, ?int $limit, array $joins);
    public function insert(string $table, array $dataToInsert);
    public function update(string $table, array $conditions, array $dataToUpdate);
    public function delete(string $table, array $conditions);
}
