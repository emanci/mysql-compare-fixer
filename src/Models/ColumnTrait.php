<?php

namespace Emanci\MysqlDiff\Models;

use Emanci\MysqlDiff\Exceptions\ColumnException;

trait ColumnTrait
{
    /**
     * @var Column[]
     */
    protected $columns = [];

    /**
     * @param Column $column
     */
    public function addColumn(Column $column)
    {
        $columnName = $column->getName();

        if ($this->hasColumn($columnName)) {
            throw new ColumnException("Column already exists {$columnName}");
        }

        $this->columns[$columnName] = $column;
    }

    /**
     * @param string $columnName
     *
     * @return Column
     */
    public function getColumnByName($columnName)
    {
        if (!$this->hasColumn($columnName)) {
            throw new ColumnException("Column {$columnName} not found");
        }

        return $this->columns[$columnName];
    }

    /**
     * @param array $columns
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param string $columnName
     *
     * @return bool
     */
    public function hasColumn($columnName)
    {
        return isset($this->columns[$columnName]);
    }
}
