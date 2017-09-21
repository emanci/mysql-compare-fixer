<?php

namespace Emanci\MysqlDiff\Core\Builder;

use Emanci\MysqlDiff\Core\Parser\TableParser;
use Emanci\MysqlDiff\Models\Table;
use InvalidArgumentException;

class TableBuilder
{
    /**
     * @var TableParser
     */
    protected $tableParser;

    /**
     * TableBuilder construct.
     *
     * @param TableParser $tableParser
     */
    public function __construct(TableParser $tableParser)
    {
        $this->tableParser = $tableParser;
    }

    /**
     * @param string $statement
     *
     * @return \Emanci\MysqlDiff\Models\Table
     */
    public function buildTable($statement)
    {
        $tableStruct = $this->tableParser->parse($statement);

        if (empty($tableStruct)) {
            throw new InvalidArgumentException('Failed to parse Table');
        }

        return $this->createTableDefinition($tableStruct[0]);
    }

    /**
     * @param array $data
     *
     * @return \Emanci\MysqlDiff\Models\Table
     */
    protected function createTableDefinition($data)
    {
        $engine = array_get($data, 'engine');
        $autoIncrement = array_get($data, 'auto_increment');
        $collate = array_get($data, 'collation');
        $rowFormat = array_get($data, 'row_format');
        $comment = array_get($data, 'comment');
        $definition = array_get($data, 'definition');

        $options = compact('engine', 'autoIncrement', 'collate', 'rowFormat', 'comment', 'definition');

        return new Table($data['name'], $options);
    }
}
