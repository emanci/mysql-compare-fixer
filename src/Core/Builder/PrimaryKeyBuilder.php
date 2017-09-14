<?php

namespace Emanci\MysqlDiff\Core\Builder;

use Emanci\MysqlDiff\Contracts\BuilderInterface;
use Emanci\MysqlDiff\Core\Parser\PrimaryKeyParser;
use Emanci\MysqlDiff\Models\PrimaryKey;
use Emanci\MysqlDiff\Models\Table;

class PrimaryKeyBuilder implements BuilderInterface
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
