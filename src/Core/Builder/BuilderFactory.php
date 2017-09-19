<?php

namespace Emanci\MysqlDiff\Core\Builder;

use Emanci\MysqlDiff\Core\Parser\ColumnParser;
use Emanci\MysqlDiff\Core\Parser\PrimaryKeyParser;
use Emanci\MysqlDiff\Core\Parser\SchemaParser;
use Emanci\MysqlDiff\Core\Parser\TableParser;
use Emanci\MysqlDiff\Models\Table;

class BuilderFactory
{
    /**
     * @return SchemaBuilder
     */
    public static function createSchemaBuilder()
    {
        return new SchemaBuilder(new SchemaParser());
    }

    /**
     * @return TableBuilder
     */
    public static function createTableBuilder()
    {
        return new TableBuilder(new TableParser());
    }

    /**
     * @return ColumnBuilder
     */
    public static function createColumnBuilder()
    {
        return new ColumnBuilder(new ColumnParser());
    }

    /**
     * @param Table $table
     *
     * @return PrimaryKeyBuilder
     */
    public static function createPrimaryKeyBuilder(Table $table)
    {
        return new PrimaryKeyBuilder($table, new PrimaryKeyParser());
    }
}
