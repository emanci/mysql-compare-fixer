<?php

namespace Emanci\MysqlDiff\Core;

use Emanci\MysqlDiff\Core\Builder\BuilderFactory;
use Emanci\MysqlDiff\Database\Mysql;
use Emanci\MysqlDiff\Models\Schema;
use Emanci\MysqlDiff\Models\Table;

class SchemaManager
{
    /**
     * @var Mysql
     */
    protected $server;

    /**
     * SchemaManager construct.
     *
     * @param Mysql $server
     */
    public function __construct(Mysql $server)
    {
        $this->server = $server;
    }

    /**
     * @return \Emanci\MysqlDiff\Models\Schema
     */
    public function createSchema()
    {
        $schemaSql = $this->server->getCreateSchemaSql();
        $schemaBuilder = BuilderFactory::createSchemaBuilder();
        $schema = $schemaBuilder->buildSchema($schemaSql);
        $this->parseTables($schema);

        return $schema;
    }

    /**
     * @param Schema $schema
     */
    public function parseTables(Schema $schema)
    {
        $tables = $this->server->tables();
        $tableBuilder = BuilderFactory::createTableBuilder();

        foreach ($tables as $tableName) {
            $tableSql = $this->server->getCreateTableSql($tableName);
            $table = $tableBuilder->buildTable($tableSql);
            $this->parseTableColumn($table);
            $this->parseTableIndex($table);
            $this->parsePrimaryKey($table);
            $this->parseForeignKey($table);
            $schema->addTable($table);
        }
    }

    /**
     * @param Table $table
     */
    protected function parseTableColumn(Table $table)
    {
        $definition = $table->getDefinition();
        $columnBuilder = BuilderFactory::createColumnBuilder();
        $columns = $columnBuilder->buildTableColumns($definition);

        foreach ($columns as $column) {
            $table->addColumn($column);
        }
    }

    /**
     * @param Table $table
     */
    protected function parseTableIndex(Table $table)
    {
        $definition = $table->getDefinition();
        $indexBuilder = BuilderFactory::createIndexBuilder($table);
        $indexes = $indexBuilder->buildIndexes($definition);

        foreach ($indexes as $index) {
            $table->addIndex($index);
        }
    }

    /**
     * @param Table $table
     */
    protected function parsePrimaryKey(Table $table)
    {
        $definition = $table->getDefinition();
        $primaryKeyBuilder = BuilderFactory::createPrimaryKeyBuilder($table);
        $primaryKey = $primaryKeyBuilder->buildPrimaryKey($definition);
        $table->setPrimaryKey($primaryKey);
    }

    /**
     * @param Table $table
     */
    protected function parseForeignKey(Table $table)
    {
        $definition = $table->getDefinition();
        $foreignKeyBuilder = BuilderFactory::createForeignKeyBuilder();
        $foreignKeys = $foreignKeyBuilder->buildForeignKeys($definition);

        foreach ($foreignKeys as $foreignKey) {
            $table->addForeignKey($foreignKey);
        }
    }
}
