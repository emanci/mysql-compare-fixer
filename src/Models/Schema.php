<?php

namespace Emanci\MysqlDiff\Models;

use Emanci\MysqlDiff\Exceptions\TableException;

/**
 * @link https://dev.mysql.com/doc/refman/5.7/en/information-schema.html
 */
class Schema extends AbstractAsset
{
    /**
     * @var Table[]
     */
    protected $tables = [];

    /**
     * @var string
     */
    protected $characterSet;

    /**
     * @var string
     */
    protected $collate;

    /**
     * Schema construct.
     *
     * @param string $name
     * @param array  $attributes
     */
    public function __construct($name, array $attributes = [])
    {
        $this->setName($name);
        $this->map($attributes);
    }

    /**
     * @param string $characterSet
     *
     * @return $this
     */
    public function setCharacterSet($characterSet)
    {
        $this->characterSet = $characterSet;

        return $this;
    }

    /**
     * @return string
     */
    public function getCharacterSet()
    {
        return $this->characterSet;
    }

    /**
     * @param string $collate
     *
     * @return $this
     */
    public function setCollate($collate)
    {
        $this->collate = $collate;

        return $this;
    }

    /**
     * @return string
     */
    public function getCollate()
    {
        return $this->collate;
    }

    /**
     * @param array $tables
     *
     * @return $this
     */
    public function addTables(array $tables)
    {
        foreach ($tables as $table) {
            $this->addTable($table);
        }

        return $this;
    }

    /**
     * @param Table $table
     */
    public function addTable(Table $table)
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
     * @param array  $attributes
     *
     * @return Table
     */
    public function createTable($tableName, array $attributes = [])
    {
        $table = new Table($tableName, $attributes);
        $this->addTable($table);

        return $table;
    }
}
