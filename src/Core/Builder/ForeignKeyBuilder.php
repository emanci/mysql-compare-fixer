<?php

namespace Emanci\MysqlDiff\Core\Builder;

use Emanci\MysqlDiff\Core\Parser\ForeignKeyParser;
use Emanci\MysqlDiff\Models\ForeignKey;

class ForeignKeyBuilder
{
    /**
     * @var TableParser
     */
    protected $foreignKeyParser;

    /**
     * ForeignKeyBuilder construct.
     *
     * @param ForeignKeyParser $foreignKeyParser
     */
    public function __construct(ForeignKeyParser $foreignKeyParser)
    {
        $this->foreignKeyParser = $foreignKeyParser;
    }

    /**
     * @param string $statement
     *
     * @return \Emanci\MysqlDiff\Models\ForeignKey[]
     */
    public function buildForeignKeys($statement)
    {
        $tableForeignKeys = $this->foreignKeyParser->parse($statement);

        $foreignKeys = array_map(function ($foreignKey) {
            return $this->createForeignKeyDefinition($foreignKey);
        }, $tableForeignKeys);

        return $foreignKeys;
    }

    /**
     * @param array $data
     *
     * @return \Emanci\MysqlDiff\Models\ForeignKey
     */
    protected function createForeignKeyDefinition($data)
    {
        $columnName = array_get($data, 'column_name');
        $referencedTableName = array_get($data, 'referenced_table');
        $referencedColumnName = array_get($data, 'reference_column');
        $onDeleteClause = array_get($data, 'on_delete');
        $onUpdateClause = array_get($data, 'on_update');

        $options = compact('columnName', 'referencedTableName', 'referencedColumnName', 'onDeleteClause', 'onUpdateClause');

        return new ForeignKey($data['index_name'], $options);
    }
}
