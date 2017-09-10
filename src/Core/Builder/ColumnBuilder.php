<?php

namespace Emanci\MysqlDiff\Core\Builder;

use Emanci\MysqlDiff\Contracts\BuilderInterface;
use Emanci\MysqlDiff\Core\Parser\ColumnParser;
use Emanci\MysqlDiff\Models\Column;

class ColumnBuilder implements BuilderInterface
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
     * @return Schema
     */
    public function create($statement)
    {
        $result = $this->columnParser->parse($statement);

        if (empty($result)) {
            return false;
        }

        $options = $this->buildColumnDefinition($result);

        return new Column($result['name'], $options);
    }

    /**
     * @param array $columnRow
     *
     * @return array
     */
    protected function buildColumnDefinition($columnRow)
    {
        $type = strtok($columnRow['data_type'], '(), ');
        $length = strtok('(), ');
        $precision = null;
        $scale = null;

        if (preg_match('([A-Za-z]+\(([0-9]+)\,([0-9]+)\))', $columnRow['data_type'], $match)) {
            $precision = $match[1];
            $scale = $match[2];
            $length = null;
        }

        $unsigned = boolval(strpos($columnRow['data_type'], 'unsigned') !== false);
        $autoIncrement = array_get($columnRow, 'auto_increment');
        $nullable = array_get($columnRow, 'nullable') == 'NOT NULL' ? true : false;
        $default = array_get($columnRow, 'default');
        $characterSet = array_get($columnRow, 'character_set');
        $collation = array_get($columnRow, 'collate');
        $comment = array_get($columnRow, 'comment');

        return compact('type', 'length', 'unsigned', 'autoIncrement', 'nullable', 'default', 'precision', 'scale', 'characterSet', 'collation', 'comment');
    }
}
