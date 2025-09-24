<?php

namespace Core\Database\MysqlDriver;

class DatabaseInFilter {    

    public static function make(string $column, array $values): DatabaseInFilter
    {
        $databaseFilter = new DatabaseInFilter();

        $databaseFilter->column = $column;
        $databaseFilter->values = $values;

        return $databaseFilter;
    }

    private string $column;
    private array $values;

    private function __construct()
    {}

    public function getValue(): array
    {
        return $this->values;
    }

    public function mountCondition(): string
    {
        $arrangeOfQuestionMarks = array_fill(0, count($this->values), "?");
        $arrangeOfQuestionMarks = implode(", ", $arrangeOfQuestionMarks);

        return "`$this->column` IN ($arrangeOfQuestionMarks)";
    }
}
