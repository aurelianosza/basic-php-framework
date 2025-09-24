<?php

namespace Core\Database;

use Core\Database\MysqlDriver\DatabaseFilter;
use Error;

abstract class Entity {

    public static string $table;

    public static function repository(): BaseRepository
    {
        if (!static::$table) {
            throw new Error("Table ain`t defined");
        }

        return new BaseRepository(static::$table, [static::class, "fromArray"]);
    }

    public static function fromArray(array $propertiesData): self
    {
        $definition = new static();
        $definition->fill($propertiesData);

        return $definition;
    }

    public int $id;

    public function fill(array $propertiesData): self
    {
        foreach($propertiesData as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }

        return $this;
    }

    public function update(array $dataToUpdate): array
    {
        if (!isset($this->id)) {
            throw new Error("Entity aint has primary key, save this first time before update.");
        }

        $repository = new BaseRepository(static::$table);

        $dataUpdated = $repository->update([
                DatabaseFilter::make("id", $this->id)
            ], $dataToUpdate);

        $this->fill($dataToUpdate);

        return $dataUpdated;
    }

    public function delete(): array
    {
        if (!isset($this->id)) {
            throw new Error("Entity ain`t has primary key, save this first time before update.");
        }

        $repository = new BaseRepository(static::$table);

        return $repository->delete([
            DatabaseFilter::make("id", $this->id)
        ]);
    }
}
