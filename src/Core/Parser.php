<?php

namespace Emanci\MysqlDiff\Core;

use Emanci\MysqlDiff\Database\Mysql;
use Emanci\MysqlDiff\Models\Column;
use Emanci\MysqlDiff\Models\ForeignKey;
use Emanci\MysqlDiff\Models\Index;
use Emanci\MysqlDiff\Models\IndexColumn;
use Emanci\MysqlDiff\Models\PrimaryKey;
use Emanci\MysqlDiff\Models\Schema;
use Emanci\MysqlDiff\Models\Table;

class Parser
{
    /**
     * @var Mysql
     */
    protected $platform;

    /**
     * Parser construct.
     *
     * @param Mysql $platform
     */
    public function __construct(Mysql $platform)
    {
        $this->platform = $platform;
    }

    /**
     * Set the Platform instance.
     *
     * @param Mysql $platform
     */
    public function setPlatform(Mysql $platform)
    {
        $this->platform = $platform;

        return $this;
    }

    /**
     * Returns the Platform instance.
     *
     * @return Mysql
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    public function parseSchema()
    {
        $schema = new Schema();
        $masterTables = $this->platform->tables();

        foreach ($masterTables as $tableRow) {
            $tableRow = $this->formatTableRow($tableRow);
            $table = $schema->createTable($tableRow['name'], $tableRow);
            $this->parseColumns($table);
            $this->parseForeignKeys($table);
            $this->parseIndexes($table);
        }

        return $schema;
    }

    /**
     * @param Table $table
     */
    protected function parseIndexes(Table $table)
    {
        $tableName = $table->getName();
        $indexes = $this->platform->indexes($tableName);

        foreach ($indexes as $indexRow) {
            $indexRow = array_change_key_case($indexRow, CASE_LOWER);

            if ('PRIMARY' == $indexRow['key_name']) {
                continue;
            }

            if ($index = $table->getIndexByName($indexRow['key_name'])) {
                $indexColumn = $this->createTableIndexColumnDefinition($table, $indexRow);
                $index->addIndexColumn($indexColumn);
            } else {
                $index = $this->createTableIndexDefinition($indexRow);
                $indexColumn = $this->createTableIndexColumnDefinition($table, $indexRow);
                $index->addIndexColumn($indexColumn);
                $table->addIndex($index);
            }
        }
    }

    /**
     * @param array $indexRow
     *
     * @return Index
     */
    protected function createTableIndexDefinition(array $indexRow)
    {
        $unique = $indexRow['non_unique'] ? false : true;
        $flags = [];

        if (strpos($indexRow['index_type'], 'FULLTEXT') !== false) {
            $flags = ['FULLTEXT'];
        } elseif (strpos($indexRow['index_type'], 'SPATIAL') !== false) {
            $flags = ['SPATIAL'];
        }

        $attributes = compact('unique', 'flags');

        return new Index($indexRow['key_name'], $attributes);
    }

    /**
     * @param Table $table
     * @param array $indexRow
     *
     * @return IndexColumn
     */
    protected function createTableIndexColumnDefinition(Table $table, array $indexRow)
    {
        $column = $table->getColumnByName($indexRow['column_name']);
        $subPart = $indexRow['sub_part'];

        return new IndexColumn($column, $subPart);
    }

    /**
     * @param array $tableRow
     *
     * @return array
     */
    protected function formatTableRow($tableRow)
    {
        $name = $tableRow['name'];
        $engine = $tableRow['engine'];
        $autoIncrement = $tableRow['auto_increment'];
        $collation = $tableRow['collation'];
        $rowFormat = $tableRow['row_format'];
        $comment = $tableRow['comment'];

        return compact('name', 'engine', 'autoIncrement', 'collation', 'rowFormat', 'comment');
    }

    /**
     * @param Table $table
     */
    protected function parseForeignKeys(Table $table)
    {
        $tableName = $table->getName();
        $foreignKeys = $this->platform->foreignKeys($tableName);

        foreach ($foreignKeys as $foreignKeyRow) {
            $foreignKeyObject = $this->createTableForeignKeyDefinition($foreignKeyRow);
            $table->addForeignKey($foreignKeyObject);
        }
    }

    /**
     * @param array $foreignKeyRow
     *
     * @return ForeignKey
     */
    protected function createTableForeignKeyDefinition(array $foreignKeyRow)
    {
        $columnName = $foreignKeyRow['column_name'];
        $referencedTableName = $foreignKeyRow['referenced_table_name'];
        $referencedColumnName = $foreignKeyRow['referenced_column_name'];
        $onDeleteClause = $foreignKeyRow['delete_rule'];
        $onUpdateClause = $foreignKeyRow['update_rule'];
        $attributes = compact('columnName', 'referencedTableName', 'referencedColumnName', 'onDeleteClause', 'onUpdateClause');

        return new ForeignKey($foreignKeyRow['constraint_name'], $attributes);
    }

    /**
     * @param Table $table
     */
    protected function parseColumns(Table $table)
    {
        $tableName = $table->getName();
        $tableColumns = $this->platform->columns($tableName);
        $primaryKeyColumns = [];

        foreach ($tableColumns as $columnRow) {
            $columnObject = $this->createTableColumnDefinition($columnRow);
            $table->addColumn($columnObject);

            if ($columnObject->isPrimaryKey()) {
                $primaryKeyColumns[] = $columnObject;
            }
        }
        $primaryKey = $this->createTablePrimaryKeyDefinition($primaryKeyColumns);
        $table->setPrimaryKey($primaryKey);
    }

    /**
     * @param Column[] $columns
     *
     * @return PrimaryKey
     */
    protected function createTablePrimaryKeyDefinition(array $columns)
    {
        return new PrimaryKey($columns);
    }

    /**
     * @param array $columnRow
     *
     * @return Column
     */
    protected function createTableColumnDefinition(array $columnRow)
    {
        $columnRow = array_change_key_case($columnRow, CASE_LOWER);
        $type = strtolower($columnRow['type']);
        $type = strtok($type, '(), ');

        if (isset($columnRow['length'])) {
            $length = $columnRow['length'];
        } else {
            $length = strtok('(), ');
        }

        $unsigned = boolval(strpos($columnRow['type'], 'unsigned') !== false);
        $autoIncrement = boolval(strpos($columnRow['extra'], 'auto_increment') !== false);
        // things like on update CURRENT_TIMESTAMP ?
        $extra = strpos($columnRow['extra'], 'auto_increment') !== false ? '' : $columnRow['extra'];
        $nullable = boolval($columnRow['null'] != 'YES');
        $default = isset($columnRow['default']) ? $columnRow['default'] : null;
        $key = $columnRow['key'];
        $characterSet = $columnRow['characterset'];
        $collation = $columnRow['collation'];
        $comment = $columnRow['comment'] !== '' ? $columnRow['comment'] : null;
        $attributes = compact('type', 'length', 'unsigned', 'autoIncrement', 'nullable', 'default', 'key', 'extra', 'characterSet', 'collation', 'comment');

        return new Column($columnRow['field'], $attributes);
    }
}
