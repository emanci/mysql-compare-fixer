<?php

namespace Emanci\MysqlDiff\Core\Builder;

use Emanci\MysqlDiff\Contracts\BuilderInterface;
use Emanci\MysqlDiff\Core\Parser\PrimaryKeyParser;
use Emanci\MysqlDiff\Models\PrimaryKey;
use Emanci\MysqlDiff\Models\Table;

class PrimaryKeyBuilder implements BuilderInterface
{
    /**
     * @var PrimaryKeyParser
     */
    protected $primaryKeyParser;

    /**
     * @var Table
     */
    protected $table;

    /**
     * PrimaryKeyBuilder construct.
     *
     * @param PrimaryKeyParser $primaryKeyParser
     * @param Table            $table
     */
    public function __construct(PrimaryKeyParser $primaryKeyParser, Table $table)
    {
        $this->primaryKeyParser = $primaryKeyParser;
        $this->table = $table;
    }

    /**
     * @param string $statement
     *
     * @return Schema
     */
    public function create($statement)
    {
        $result = $this->primaryKeyParser->parse($statement);

        if (empty($result)) {
            return false;
        }

        $primaryKeyNames = explode(',', str_replace('`', '', $result['primary_key']));
        $primaryKeyColumns = [];

        foreach ($primaryKeyNames as $primaryKeyName) {
            $primaryKeyColumns[] = $this->table->getColumnByName($primaryKeyName);
        }

        return new PrimaryKey($primaryKeyColumns);
    }
}
