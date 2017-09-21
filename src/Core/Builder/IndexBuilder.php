<?php

namespace Emanci\MysqlDiff\Core\Builder;

use Emanci\MysqlDiff\Core\Parser\IndexParser;
use Emanci\MysqlDiff\Models\Index;

class IndexBuilder
{
    /**
     * @var IndexParser
     */
    protected $indexParser;

    /**
     * @var IndexColumnBuilder
     */
    protected $indexColumnBuilder;

    /**
     * IndexBuilder construct.
     *
     * @param IndexParser        $indexParser
     * @param IndexColumnBuilder $indexColumnBuilder
     */
    public function __construct(IndexParser $indexParser, IndexColumnBuilder $indexColumnBuilder)
    {
        $this->indexParser = $indexParser;
        $this->indexColumnBuilder = $indexColumnBuilder;
    }

    /**
     * @param string $statement
     *
     * @return \Emanci\MysqlDiff\Models\Index[]
     */
    public function buildIndexes($statement)
    {
        $tableIndexes = $this->indexParser->parse($statement);

        $indexes = array_map(function ($tableIndex) {
            return $this->createIndexDefinition($tableIndex);
        }, $tableIndexes);

        return $indexes;
    }

    /**
     * @param array $tableIndex
     *
     * @return \Emanci\MysqlDiff\Models\Index
     */
    protected function createIndexDefinition($tableIndex)
    {
        $spatial = array_get($tableIndex, 'spatial');
        $fullText = array_get($tableIndex, 'fullText');
        $indexColumns = $this->indexColumnBuilder->buildIndexColumns($tableIndex['columns']);

        $options = [
            'unique' => array_get($tableIndex, 'unique'),
            'flags' => $spatial ? $spatial : $fullText,
            'indexColumns' => $indexColumns,
            'options' => array_get($tableIndex, 'options'),
        ];

        return new Index($tableIndex['name'], $options);
    }
}
