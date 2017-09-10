<?php

namespace Emanci\MysqlDiff\Core\Builder;

use Emanci\MysqlDiff\Contracts\BuilderInterface;
use Emanci\MysqlDiff\Core\Parser\TableParser;
use Emanci\MysqlDiff\Models\Table;
use InvalidArgumentException;

class TableBuilder implements BuilderInterface
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
     * @return Schema
     */
    public function create($statement)
    {
        $result = $this->tableParser->parse($statement);

        if (empty($result)) {
            throw new InvalidArgumentException('Failed to parse Table');
        }

        $tableRow = $this->formatTableRow($result);

        return new Table($result['name'], $tableRow);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function formatTableRow($data)
    {
        $engine = array_get($data, 'engine');
        $autoIncrement = array_get($data, 'auto_increment');
        $collate = array_get($data, 'collation');
        $rowFormat = array_get($data, 'row_format');
        $comment = array_get($data, 'comment');
        $definition = array_get($data, 'definition');

        return compact('engine', 'autoIncrement', 'collate', 'rowFormat', 'comment', 'definition');
    }
}
