<?php

namespace Emanci\MysqlDiff\Models;

use Emanci\MysqlDiff\Exceptions\IndexException;

/**
 * @link https://dev.mysql.com/doc/refman/5.7/en/charset-table.html
 * @link https://dev.mysql.com/doc/refman/5.6/en/alter-table.html
 */
class Table extends AbstractAsset
{
    use ColumnTrait;

    /**
     * @var PrimaryKey
     */
    protected $primaryKey;

    /**
     * @var Index[]
     */
    protected $indexes = [];

    /**
     * @var ForeignKey[]
     */
    protected $foreignKeys = [];

    /**
     * @var string
     */
    protected $engine;

    /**
     * @var int|null
     */
    protected $autoIncrement;

    /**
     * @var string
     */
    protected $collate;

    /**
     * @var string
     */
    protected $rowFormat;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var string
     */
    protected $definition;

    /**
     * Table construct.
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
     * @return Index[]
     */
    public function getIndexes()
    {
        return $this->indexes;
    }

    /**
     * @param Index $index
     */
    public function addIndex(Index $index)
    {
        $indexName = $index->getName();

        if ($this->hasIndex($indexName)) {
            throw new IndexException("Index already exists {$indexName}");
        }

        $this->indexes[$indexName] = $index;
    }

    /**
     * @param string     $columnName
     * @param mixed|null $default
     *
     * @return Column
     */
    public function getIndexByName($indexName, $default = null)
    {
        if ($this->hasIndex($indexName)) {
            return $this->indexes[$indexName];
        }

        return $default;
    }

    /**
     * @param string $indexName
     *
     * @return bool
     */
    public function hasIndex($indexName)
    {
        return isset($this->indexes[$indexName]);
    }

    /**
     * @return ForeignKey[]
     */
    public function getForeignKeys()
    {
        return $this->foreignKeys;
    }

    /**
     * @param ForeignKey $foreignKey
     */
    public function addForeignKey(ForeignKey $foreignKey)
    {
        $foreignKeyName = $foreignKey->getName();

        if ($this->hasForeignKey($foreignKeyName)) {
            throw new ForeignKeyException("ForeignKey already exists {$foreignKeyName}");
        }

        $this->foreignKeys[$foreignKeyName] = $foreignKey;
    }

    /**
     * @param string $foreignKeyName
     *
     * @return bool
     */
    public function hasForeignKey($foreignKeyName)
    {
        return isset($this->foreignKeys[$foreignKeyName]);
    }

    /**
     * @return string
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @param string $engine
     *
     * @return $this
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAutoIncrement()
    {
        return $this->autoIncrement;
    }

    /**
     * @param int $autoIncrement
     *
     * @return $this
     */
    public function setAutoIncrement($autoIncrement)
    {
        $this->autoIncrement = $autoIncrement;

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
    public function getRowFormat()
    {
        return $this->rowFormat;
    }

    /**
     * @param string $rowFormat
     *
     * @return $this
     */
    public function setRowFormat($rowFormat)
    {
        $this->rowFormat = $rowFormat;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @param string $definition
     *
     * @return $this
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * @return PrimaryKey
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @param PrimaryKey
     *
     * @return $this
     */
    public function setPrimaryKey(PrimaryKey $primaryKey)
    {
        $this->primaryKey = $primaryKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getCreateTableSql()
    {
        $tableDefinitions = [];

        // column
        foreach ($this->columns as $column) {
            $tableDefinitions[] = $column->getTableColumnDefinition();
        }

        // primary key
        $tableDefinitions[] = $this->primaryKey->getPrimaryKeyDefinition();

        // index
        foreach ($this->indexes as $index) {
            $tableDefinitions[] = $index->getTableIndexDefinition();
        }

        // foreign key
        foreach ($this->foreignKeys as $foreignKey) {
            $tableDefinitions[] = $foreignKey->getForeignKeyDefinition();
        }

        $tableDefinition = implode(', ', $tableDefinitions);

        // table option
        $tableOptionDefinition = $this->getTableOptionDefinition();

        return sprintf(
            'CREATE TABLE IF NOT EXISTS `%s` (%s) %s;',
            $this->name,
            $tableDefinition,
            $tableOptionDefinition
        );
    }

    /**
     * @return string
     */
    protected function getTableOptionDefinition()
    {
        $tableOptions = [];

        if ($this->engine) {
            $tableOptions[] = sprintf('ENGINE=%s', $this->engine);
        }

        if ($this->autoIncrement) {
            $tableOptions[] = sprintf('AUTO_INCREMENT=%s', $this->autoIncrement);
        }

        if ($this->collate) {
            $tableOptions[] = sprintf('COLLATE=%s', $this->collate);
        }

        if ($this->comment) {
            $tableOptions[] = sprintf('COMMENT=\'%s\'', $this->comment);
        }

        if ($this->rowFormat) {
            $tableOptions[] = sprintf('ROW_FORMAT=%s', $this->rowFormat);
        }

        return implode(' ', $tableOptions);
    }
}
