<?php

namespace Emanci\MysqlDiff\Core\Builder;

use Emanci\MysqlDiff\Core\Parser\IndexColumnParser;
use Emanci\MysqlDiff\Models\IndexColumn;
use Emanci\MysqlDiff\Models\Table;

class IndexColumnBuilder
{
    /**
     * @var Table
     */
    protected $table;

    /**
     * @var IndexColumnParser
     */
    protected $indexColumnParser;

    /**
     * IndexColumnBuilder construct.
     *
     * @param Table             $table
     * @param IndexColumnParser $indexColumnParser
     */
    public function __construct(Table $table, IndexColumnParser $indexColumnParser)
    {
        $this->table = $table;
        $this->indexColumnParser = $indexColumnParser;
    }

    /**
     * @param string $statement
     *
     * @return \Emanci\MysqlDiff\Models\IndexColumn
     */
    public function buildIndexColumns($statement)
    {
        $indexColumnNames = explode(',', str_replace('`', '', $statement));
        $indexColumns = array_map(function ($indexColumnName) {
            $tableIndexColumn = $this->indexColumnParser->parse($indexColumnName);

            return $this->createIndexColumnDefinition($tableIndexColumn[0]);
        }, $indexColumnNames);

        return $indexColumns;
    }

    /**
     * @param array $tableIndexColumn
     *
     * @return \Emanci\MysqlDiff\Models\IndexColumn
     */
    protected function createIndexColumnDefinition($tableIndexColumn)
    {
        $column = $this->table->getColumnByName($tableIndexColumn['column_name']);
        $subPart = array_get($tableIndexColumn, 'sub_part');

        return new IndexColumn($column, $subPart);
    }
}
