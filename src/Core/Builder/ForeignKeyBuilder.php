<?php

namespace Emanci\MysqlDiff\Core\Builder;

use Emanci\MysqlDiff\Contracts\BuilderInterface;
use Emanci\MysqlDiff\Core\Parser\ForeignKeyParser;
use Emanci\MysqlDiff\Models\ForeignKey;

class ForeignKeyBuilder implements BuilderInterface
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
     * @return Schema
     */
    public function create($statement)
    {
        $tableForeignKey = $this->foreignKeyParser->parse($statement);

        if (empty($tableForeignKey)) {
            return false;
        }

        $options = $this->buildForeignKeyDefinition($tableForeignKey);

        return new ForeignKey($tableForeignKey['index_name'], $options);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function buildForeignKeyDefinition($data)
    {
        $columnName = array_get($data, 'column_name');
        $referencedTableName = array_get($data, 'referenced_table');
        $referencedColumnName = array_get($data, 'reference_column');
        $onDeleteClause = array_get($data, 'on_delete');
        $onUpdateClause = array_get($data, 'on_update');

        return compact('columnName', 'referencedTableName', 'referencedColumnName', 'onDeleteClause', 'onUpdateClause');
    }
}
