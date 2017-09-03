<?php

namespace Emanci\MysqlDiff\Model;

use Emanci\MysqlDiff\Exceptions\TableException;

class Schema
{
    /**
     * @var Table[]
     */
    protected $tables;

    /**
     * Schema construct.
     *
     * @param array $tables
     */
    public function __construct(array $tables = [])
    {
        foreach ($tables as $table) {
            $this->addTable($table);
        }
    }

    /**
     * @param Table $table
     */
    protected function addTable(Table $table)
    {
        $tableName = $table->getName();

        if ($this->hasTable($tableName)) {
            throw new TableException("Table already exists {$tableName}");
        }

        $this->tables[$tableName] = $table;
    }

    /**
     * Gets all tables of this schema.
     *
     * @return Table[]
     */
    public function getTables()
    {
        return $this->tables;
    }

    /**
     * @param string $tableName
     *
     * @return Table
     */
    public function getTable($tableName)
    {
        if (!$this->hasTable($tableName)) {
            throw new TableException("Table does not exist {$tableName}");
        }

        return $this->tables[$tableName];
    }

    /**
     * @param string $tableName
     *
     * @return bool
     */
    public function hasTable($tableName)
    {
        return isset($this->tables[$tableName]);
    }

    /**
     * @return array
     */
    public function getTableNames()
    {
        return array_keys($this->tables);
    }

    /**
     * @param string $tableName
     *
     * @return Table
     */
    public function createTable($tableName)
    {
        $table = new Table($tableName);
        $this->addTable($table);

        return $table;
    }
}
