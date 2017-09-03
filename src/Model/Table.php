<?php

namespace Emanci\MysqlDiff\Model;

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
     * @var ForeignKey
     */
    protected $foreignKeys;

    /**
     * @var string
     */
    protected $engine;

    /**
     * @var int
     */
    protected $autoIncrement;

    /**
     * @var string
     */
    protected $defaultCharset;

    /**
     * @var string
     */
    protected $collate;

    /**
     * @var string
     */
    protected $comment;

    /**
     * Table construct.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
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
        $this->foreignKeys[$foreignKeyName] = $foreignKey;

        return $this->foreignKeys[$foreignKeyName];
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
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;

        return $this;
    }

    /**
     * @return int
     */
    public function getAutoIncrement()
    {
        return $this->autoIncrement;
    }

    /**
     * @param int $autoIncrement
     */
    public function setAutoIncrement($autoIncrement)
    {
        $this->autoIncrement = $autoIncrement;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultCharset()
    {
        return $this->defaultCharset;
    }

    /**
     * @param string $defaultCharset
     */
    public function setDefaultCharset($defaultCharset)
    {
        $this->defaultCharset = $defaultCharset;

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
     */
    public function setCollate($collate)
    {
        $this->collate = $collate;

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
     */
    public function setPrimaryKey(PrimaryKey $primaryKey)
    {
        $this->primaryKey = $primaryKey;

        return $this;
    }
}
