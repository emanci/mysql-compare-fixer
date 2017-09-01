<?php

namespace Emanci\MysqlCompareFixer\Model;

use Emanci\MysqlCompareFixer\Exceptions\ColumnException;

trait ColumnTrait
{
    /**
     * @var \Emanci\MysqlCompareFixer\Model\Column[]
     */
    protected $columns = [];

    /**
     * @param Column $column
     */
    public function addColumn(Column $column)
    {
        $columnField = $column->getField();

        if ($this->hasColumn($columnField)) {
            throw new ColumnException("Column already exists {$columnField}");
        }

        $this->columns[$columnField] = $column;
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
     * @return \Emanci\MysqlCompareFixer\Model\Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param string $columnField
     *
     * @return bool
     */
    public function hasColumn($columnField)
    {
        return isset($this->columns[$columnField]);
    }
}
