<?php

namespace Core\Database\MysqlDriver;

use Error;

class DatabaseFilter {    

    public static function make(string $column, string $operator, null|string|array $value = null): DatabaseFilter
    {
        $databaseFilter = new DatabaseFilter();

        $databaseFilter->column = $column;
        $databaseFilter->operator = $value == null
            ? "="
            : $operator;
        $databaseFilter->value = $value == null
            ? $operator
            : $value;

        return $databaseFilter;
    }

    private string $column;
    private string $operator;
    private string|array $value;

    private function __construct()
    {}

    public function getValue(): array
    {
        return $this->operator == "IN"
            ? $this->value
            : [$this->value];
    }

    public function mountCondition(): string
    {
        return match($this->operator) {
            "IN" => $this->mountConditionToInClause(),
            "=" => "$this->column = ?",
            "LIKE" => "$this->column LIKE ?",
            default => throw new Error("database operation to $this->operator aint make")
        };
    }

    private function mountConditionToInClause(): string
    {
        $arrangeOfQuestionMarks = array_fill(0, count($this->value), "?");
        $arrangeOfQuestionMarks = implode(", ", $arrangeOfQuestionMarks);

        return "$this->column IN ($arrangeOfQuestionMarks)";   
    }
}
