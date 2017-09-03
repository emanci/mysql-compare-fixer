<?php

namespace Emanci\MysqlDiff\Model;

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
