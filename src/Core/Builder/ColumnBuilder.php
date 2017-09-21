<?php

namespace Emanci\MysqlDiff\Core\Builder;

use Emanci\MysqlDiff\Core\Parser\ColumnParser;
use Emanci\MysqlDiff\Models\Column;

class ColumnBuilder
{
    /**
     * @var TableParser
     */
    protected $columnParser;

    /**
     * ColumnBuilder construct.
     *
     * @param ColumnParser $columnParser
     */
    public function __construct(ColumnParser $columnParser)
    {
        $this->columnParser = $columnParser;
    }

    /**
     * @param string $statement
     *
     * @return \Emanci\MysqlDiff\Models\Column[]
     */
    public function buildTableColumns($statement)
    {
        $tableColumns = $this->columnParser->parse($statement);

        $columns = array_map(function ($tableColumn) {
            return $this->createColumnDefinition($tableColumn);
        }, $tableColumns);

        return $columns;
    }

    /**
     * @param array $tableColumn
     *
     * @return \Emanci\MysqlDiff\Models\Column
     */
    protected function createColumnDefinition($tableColumn)
    {
        $type = strtok($tableColumn['data_type'], '(), ');
        $length = strtok('(), ');
        $precision = null;
        $scale = null;

        if (preg_match('([A-Za-z]+\(([0-9]+)\,([0-9]+)\))', $tableColumn['data_type'], $match)) {
            $precision = $match[1];
            $scale = $match[2];
            $length = null;
        }

        $unsigned = boolval(strpos($tableColumn['data_type'], 'unsigned') !== false);
        $autoIncrement = array_get($tableColumn, 'auto_increment');
        $nullable = array_get($tableColumn, 'nullable') == 'NOT NULL' ? true : false;
        $default = array_get($tableColumn, 'default');
        $characterSet = array_get($tableColumn, 'character_set');
        $collation = array_get($tableColumn, 'collate');
        $comment = array_get($tableColumn, 'comment');

        $options = compact('type', 'length', 'unsigned', 'autoIncrement', 'nullable', 'default', 'precision', 'scale', 'characterSet', 'collation', 'comment');

        return new Column($tableColumn['name'], $options);
    }
}
