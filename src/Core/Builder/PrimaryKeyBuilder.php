<?php

namespace Emanci\MysqlDiff\Core\Builder;

use Emanci\MysqlDiff\Core\Parser\PrimaryKeyParser;
use Emanci\MysqlDiff\Models\PrimaryKey;
use Emanci\MysqlDiff\Models\Table;

class PrimaryKeyBuilder
{
    /**
     * @var Table
     */
    protected $table;

    /**
     * @var PrimaryKeyParser
     */
    protected $primaryKeyParser;

    /**
     * PrimaryKeyBuilder construct.
     *
     * @param Table            $table
     * @param PrimaryKeyParser $primaryKeyParser
     */
    public function __construct(Table $table, PrimaryKeyParser $primaryKeyParser)
    {
        $this->table = $table;
        $this->primaryKeyParser = $primaryKeyParser;
    }

    /**
     * @param string $statement
     *
     * @return \Emanci\MysqlDiff\Models\PrimaryKey
     */
    public function buildPrimaryKey($statement)
    {
        $primaryKeyStruct = $this->primaryKeyParser->parse($statement);

        if (empty($primaryKeyStruct)) {
            return false;
        }

        return $this->createPrimaryKeyDefinition($primaryKeyStruct[0]);
    }

    /**
     * @param array $tablePrimaryKey
     *
     * @return \Emanci\MysqlDiff\Models\PrimaryKey
     */
    protected function createPrimaryKeyDefinition($tablePrimaryKey)
    {
        $primaryKeyNames = explode(',', str_replace('`', '', $tablePrimaryKey['primary_key']));
        $primaryKeyColumns = [];

        foreach ($primaryKeyNames as $primaryKeyName) {
            $primaryKeyColumns[] = $this->table->getColumnByName($primaryKeyName);
        }

        return new PrimaryKey($primaryKeyColumns);
    }
}
