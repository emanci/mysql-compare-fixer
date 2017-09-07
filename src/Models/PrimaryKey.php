<?php

namespace Emanci\MysqlDiff\Models;

/**
 * @link https://dev.mysql.com/doc/refman/5.7/en/show-columns.html
 */
class PrimaryKey
{
    use ColumnTrait;

    /**
     * PrimaryKey construct.
     *
     * @param array $columns
     */
    public function __construct(array $columns = [])
    {
        foreach ($columns as $column) {
            $this->addColumn($column);
        }
    }

    /**
     * @return string
     */
    public function getPrimaryKeyDefinition()
    {
        $primaryKeysString = implode('`, `', $this->getPrimaryKeys());

        return sprintf('PRIMARY KEY (`%s`)', $primaryKeysString);
    }

    /**
     * @return array
     */
    protected function getPrimaryKeys()
    {
        return array_map(function ($column) {
            return $column->getName();
        }, $this->columns);
    }
}
