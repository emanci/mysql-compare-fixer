<?php

namespace Emanci\MysqlDiff\Models;

/**
 * @link https://dev.mysql.com/doc/refman/5.7/en/charset-table.html
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
    protected $indexes;

    /**
     * @var ForeignKey[]
     */
    protected $foreignKeys;

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
     * @param array $attributes
     *
     * @return $this
     */
    public function map(array $attributes)
    {
        foreach ($attributes as $name => $value) {
            $method = 'set'.$name;
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
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
}
